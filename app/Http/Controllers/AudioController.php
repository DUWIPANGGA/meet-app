<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiveAudio;
use App\Models\RekamanAudio;
use App\Models\Notulensi;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AudioController extends Controller
{
    /**
     * Tampilkan menu utama fitur Notulensi Audio
     */
    public function index()
    {
        return view('audio.index');
    }

    /**
     * Tampilkan UI Perekaman Audio
     */
    public function record()
    {
        return view('audio.record');
    }

    /**
     * [FLOW BARU] Expose URL Python Whisper backend ke FE
     * Agar URL tidak hardcode di JavaScript.
     */
    public function whisperUrl()
    {
        return response()->json([
            'url' => config('services.whisper.url', env('WHISPER_URL', 'http://127.0.0.1:8001/transcribe')),
        ]);
    }

    /**
     * [FLOW BARU] Simpan audio mentah terlebih dahulu sebelum diproses Whisper
     */
    public function saveRaw(Request $request)
    {
        $request->validate([
            'audio' => 'required|file|max:102400',
        ]);

        $file = $request->file('audio');
        $path = $file->store('live_audios', 'public');

        if (!$path || $path === '0' || $path === false) {
            \Log::error('saveRaw: store() returned invalid path', [
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan file audio ke storage.',
            ], 500);
        }

        $liveAudio = LiveAudio::create([
            'user_id'         => auth()->id(),
            'file_path'       => $path,
            'mime_type'       => $file->getMimeType(),
            'file_size_bytes' => $file->getSize(),
            'tanggal_rekam'   => now(),
            'durasi'          => 'Unknown',
        ]);

        \Log::info('saveRaw: file saved', [
            'live_audio_id' => $liveAudio->id,
            'file_path' => $path,
            'size' => $file->getSize(),
        ]);

        // Simpan juga ke RekamanAudio agar tampil di admin/rekaman-audio
        RekamanAudio::create([
            'user_id'        => auth()->id(),
            'meeting_id'     => null,
            'file_audio'     => $path,
            'durasi'          => 'Unknown',
            'tanggal_upload' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'id'     => $liveAudio->id,
        ]);
    }

    /**
     * [ASYNC] Mulai transkripsi Whisper via queue job (bypass Cloudflare timeout)
     */
    public function transcribe(Request $request)
    {
        $request->validate([
            'live_audio_id' => 'required|integer|exists:live_audios,id',
        ]);

        $liveAudio = LiveAudio::findOrFail($request->live_audio_id);

        if ($liveAudio->transcript) {
            return response()->json([
                'status' => 'completed',
                'transcript' => $liveAudio->transcript,
            ]);
        }

        \App\Jobs\LiveAudio\WhisperTranscribeJob::dispatch($liveAudio->id);

        return response()->json([
            'status' => 'processing',
            'live_audio_id' => $liveAudio->id,
        ]);
    }

    /**
     * [ASYNC] Polling status transkripsi Whisper
     */
    public function transcribeStatus(int $id)
    {
        $liveAudio = LiveAudio::findOrFail($id);

        if ($liveAudio->transcript) {
            return response()->json([
                'status' => 'completed',
                'transcript' => $liveAudio->transcript,
            ]);
        }

        return response()->json([
            'status' => 'processing',
        ]);
    }

    /**
     * [FLOW BARU] FE menyimpan hasil notulensi yang sudah diproses
     * Alur: FE kirim audio → Python/Whisper → transcript → Gemini → notulensi JSON → FE panggil endpoint ini
     *
     * Request dari FE:
     * - live_audio_id (integer, optional): ID dari audio yang sudah disimpan sebelumnya via save-raw
     * - audio (file, required jika live_audio_id null): file audio yang direkam
     * - notulensi_teks (string|JSON): hasil notulensi dari Gemini
     */
    public function save(Request $request)
    {
        $request->validate([
            'live_audio_id'  => 'nullable|integer|exists:live_audios,id',
            'audio'          => 'required_without:live_audio_id|file|max:102400',
            'notulensi_teks' => 'required|string',
            'transcript'     => 'nullable|string',
        ]);

        // Validasi bahwa notulensi_teks adalah JSON valid dari Gemini
        $decoded = json_decode($request->notulensi_teks, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'status'  => 'error',
                'message' => 'notulensi_teks bukan JSON valid: ' . json_last_error_msg(),
            ], 422);
        }

        if ($request->filled('live_audio_id')) {
            $liveAudio = LiveAudio::findOrFail($request->live_audio_id);
            $updateData = [
                'notulensi_teks' => json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            ];
            if ($request->filled('transcript') && !$liveAudio->transcript) {
                $updateData['transcript'] = $request->transcript;
            }
            $liveAudio->update($updateData);
        } else {
            // Simpan file audio (fallback behavior)
            $file = $request->file('audio');
            $path = $file->store('live_audios', 'public');

            if (!$path || $path === '0' || $path === false) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan file audio ke storage.',
                ], 500);
            }

            // Simpan ke database
            $liveAudio = LiveAudio::create([
                'user_id'         => auth()->id(),
                'file_path'       => $path,
                'mime_type'       => $file->getMimeType(),
                'file_size_bytes' => $file->getSize(),
                'tanggal_rekam'   => now(),
                'durasi'          => 'Unknown',
                'transcript'      => $request->transcript,
                'notulensi_teks'  => json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            ]);
        }

        // Simpan juga ke tabel notulensis agar tampil di admin/notulensis
        $notulensi = Notulensi::updateOrCreate(
            ['live_audio_id' => $liveAudio->id],
            [
                'meeting_id'         => null,
                'ringkasan'          => $decoded['ringkasan'] ?? '-',
                'structured_summary' => $decoded,
                'openai_model'       => 'DeepSeek AI',
                'prompt_version'     => '1.0',
                'tanggal_generate'   => now()->toDateString(),
            ]
        );

        $redirectUrl = auth()->user()?->hasAnyRole(['super_admin', 'admin'])
            ? route('admin.notulensis.show', $notulensi)
            : route('audio.show', $liveAudio->id);

        return response()->json([
            'status'       => 'success',
            'message'      => 'Notulensi berhasil disimpan!',
            'redirect_url' => $redirectUrl,
        ]);
    }

    /**
     * [LAMA] Upload audio ke queue untuk diproses background (masih dipertahankan)
     */
    public function upload(Request $request)
    {
        $request->validate([
            'audio' => 'required|file|mimes:mp3,wav,ogg,webm|max:51200'
        ]);

        $file = $request->file('audio');
        $path = $file->store('live_audios', 'public');

        if (!$path || $path === '0' || $path === false) {
            \Log::error('upload: store() returned invalid path', [
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
            ]);
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan file audio ke storage.',
            ], 500);
        }

        $liveAudio = LiveAudio::create([
            'user_id'         => auth()->id(),
            'file_path'       => $path,
            'mime_type'       => $file->getMimeType(),
            'file_size_bytes' => $file->getSize(),
            'tanggal_rekam'   => now(),
            'durasi'          => 'Unknown',
        ]);

        // Simpan juga ke RekamanAudio agar tampil di admin/rekaman-audio
        RekamanAudio::create([
            'user_id'        => auth()->id(),
            'meeting_id'     => null,
            'file_audio'     => $path,
            'durasi'          => 'Unknown',
            'tanggal_upload' => now(),
        ]);

        \App\Jobs\LiveAudio\ProcessLiveAudioJob::dispatch($liveAudio->id);

        return response()->json([
            'status'  => 'success',
            'message' => 'Audio berhasil disimpan dan sedang diproses oleh AI.',
            'path'    => $path,
        ]);
    }

    /**
     * Tampilkan riwayat notulensi milik user saat ini
     */
    public function history()
    {
        $userId = auth()->id();

        $ownAudios = LiveAudio::where('user_id', $userId)
            ->with('notulensi')
            ->orderBy('created_at', 'desc')
            ->get();

        $sharedAudios = LiveAudio::where('user_id', '!=', $userId)
            ->whereHas('notulensi', function ($q) use ($userId) {
                $q->where('akses_notulensi', 'all_users')
                    ->orWhereHas('accessUsers', function ($auq) use ($userId) {
                        $auq->where('user_id', $userId);
                    });
            })
            ->with('notulensi')
            ->orderBy('created_at', 'desc')
            ->get();

        $audios = $ownAudios->concat($sharedAudios)->sortByDesc('created_at');

        return view('audio.history', compact('audios'));
    }

    /**
     * Tampilkan detail satu notulensi audio
     */
    public function show(LiveAudio $liveAudio)
    {
        $userId = auth()->id();

        if ($liveAudio->user_id !== $userId) {
            $notulensi = $liveAudio->notulensi;
            if ($notulensi) {
                $hasAccess = $notulensi->akses_notulensi === 'all_users'
                    || $notulensi->accessUsers()->where('user_id', $userId)->exists();
                if ($hasAccess) {
                    return $this->renderShow($liveAudio);
                }
            }
            abort(403, 'Unauthorized access.');
        }

        return $this->renderShow($liveAudio);
    }

    private function renderShow(LiveAudio $liveAudio)
    {
        $notulensi = null;
        if ($liveAudio->notulensi_teks) {
            $notulensi = json_decode($liveAudio->notulensi_teks, true);
        }

        if (!$notulensi && $liveAudio->notulensi) {
            $n = $liveAudio->notulensi;
            $notulensi = [
                'ringkasan' => $n->ringkasan,
                'topik_dibahas' => $n->structured_summary['topik_dibahas'] ?? [],
                'keputusan' => $n->structured_summary['keputusan'] ?? [],
                'action_items' => $n->structured_summary['action_items'] ?? [],
                'risiko_catatan' => $n->structured_summary['risiko_catatan'] ?? [],
            ];
        }

        return view('audio.show', compact('liveAudio', 'notulensi'));
    }

    /**
     * Tampilkan form edit notulensi audio
     */
    public function edit(LiveAudio $liveAudio)
    {
        Gate::authorize('edit_notulensi');

        if ($liveAudio->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('audio.edit', compact('liveAudio'));
    }

    /**
     * Simpan perubahan notulensi yang diedit
     */
    public function update(Request $request, LiveAudio $liveAudio)
    {
        Gate::authorize('edit_notulensi');

        if ($liveAudio->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'notulensi_teks' => 'required|string',
        ]);

        $decoded = json_decode($request->notulensi_teks, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['notulensi_teks' => 'Format JSON tidak valid. Pastikan isi field benar.'])->withInput();
        }

        $liveAudio->update([
            'notulensi_teks' => $request->notulensi_teks,
        ]);

        if (auth()->user()?->hasAnyRole(['super_admin', 'admin'])) {
            $notulensi = Notulensi::where('live_audio_id', $liveAudio->id)->first();
            if ($notulensi) {
                return redirect()->route('admin.notulensis.show', $notulensi)
                    ->with('success', 'Notulensi berhasil diperbarui!');
            }
        }

        return redirect()->route('audio.show', $liveAudio->id)
            ->with('success', 'Notulensi berhasil diperbarui!');
    }

    /**
     * Download notulensi sebagai PDF
     */
    public function downloadPdf(LiveAudio $liveAudio)
    {
        $userId = auth()->id();

        if ($liveAudio->user_id !== $userId) {
            $notulensi = $liveAudio->notulensi;
            if ($notulensi) {
                $hasAccess = $notulensi->akses_notulensi === 'all_users'
                    || $notulensi->accessUsers()->where('user_id', $userId)->exists();
                if (!$hasAccess) {
                    abort(403, 'Unauthorized action.');
                }
            } else {
                abort(403, 'Unauthorized action.');
            }
        }

        $notulensi = null;
        if ($liveAudio->notulensi_teks) {
            $notulensi = json_decode($liveAudio->notulensi_teks, true);
        }

        if (!$notulensi && $liveAudio->notulensi) {
            $n = $liveAudio->notulensi;
            $notulensi = [
                'ringkasan' => $n->ringkasan,
                'topik_dibahas' => $n->structured_summary['topik_dibahas'] ?? [],
                'keputusan' => $n->structured_summary['keputusan'] ?? [],
                'action_items' => $n->structured_summary['action_items'] ?? [],
                'risiko_catatan' => $n->structured_summary['risiko_catatan'] ?? [],
            ];
        }

        $pdf = Pdf::loadView('pdf.live_audio', compact('liveAudio', 'notulensi'))
            ->setPaper('a4', 'portrait');

        $filename = 'notulensi-audio-' . $liveAudio->tanggal_rekam->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Hapus riwayat notulensi
     */
    public function destroy(LiveAudio $liveAudio)
    {
        if ($liveAudio->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($liveAudio->file_path) {
            Storage::disk('public')->delete($liveAudio->file_path);

            // Hapus juga RekamanAudio terkait
            RekamanAudio::where('file_audio', $liveAudio->file_path)->delete();
        }

        // Hapus juga Notulensi terkait
        Notulensi::where('live_audio_id', $liveAudio->id)->delete();

        $liveAudio->delete();

        if (auth()->user()?->hasAnyRole(['super_admin', 'admin'])) {
            return redirect()->route('admin.notulensis.index')->with('success', 'Riwayat notulensi berhasil dihapus.');
        }

        return redirect()->route('audio.history')->with('success', 'Riwayat notulensi berhasil dihapus.');
    }
}
