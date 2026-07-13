<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notulensi;
use App\Models\LiveAudio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class NotulensiController extends Controller
{
    public function index()
    {
        $notulensis = Notulensi::with(['meeting', 'liveAudio'])
            ->latest()
            ->paginate(20);

        return view('admin.notulensis.index', compact('notulensis'));
    }

    public function show(Notulensi $notulensi)
    {
        $notulensi->load(['meeting', 'liveAudio']);
        return view('admin.notulensis.show', compact('notulensi'));
    }

    public function edit(Notulensi $notulensi)
    {
        $notulensi->load(['meeting', 'liveAudio']);
        return view('admin.notulensis.edit', compact('notulensi'));
    }

    public function downloadPdf(Notulensi $notulensi)
    {
        if ($notulensi->file_pdf) {
            $disk = Storage::disk('local');
            if ($disk->exists($notulensi->file_pdf)) {
                $filename = 'notulensi-'.$notulensi->id.'.pdf';
                return response()->download($disk->path($notulensi->file_pdf), $filename);
            }
        }

        if ($notulensi->live_audio_id && $notulensi->liveAudio) {
            $liveAudio = $notulensi->liveAudio;
            $notulensiData = [
                'ringkasan' => $notulensi->ringkasan,
                'topik_dibahas' => $notulensi->structured_summary['topik_dibahas'] ?? [],
                'keputusan' => $notulensi->structured_summary['keputusan'] ?? [],
                'action_items' => $notulensi->structured_summary['action_items'] ?? [],
                'risiko_catatan' => $notulensi->structured_summary['risiko_catatan'] ?? [],
            ];

            $pdf = Pdf::loadView('pdf.live_audio', ['liveAudio' => $liveAudio, 'notulensi' => $notulensiData])
                ->setPaper('a4', 'portrait');

            $filename = 'notulensi-audio-'.$notulensi->id.'.pdf';
            return $pdf->download($filename);
        }

        abort(404, 'PDF notulensi belum tersedia.');
    }

    public function update(Request $request, Notulensi $notulensi)
    {
        $validated = $request->validate([
            'ringkasan' => 'nullable|string',
            'structured_summary' => 'nullable|array',
            'nama_rapat' => 'nullable|string|max:255',
        ]);

        if (isset($validated['structured_summary'])) {
            $s = $validated['structured_summary'];

            if (isset($s['topik_dibahas'])) {
                $s['topik_dibahas'] = array_values(array_filter($s['topik_dibahas'], fn($v) => !is_null($v) && $v !== ''));
            }
            if (isset($s['keputusan'])) {
                $s['keputusan'] = array_values(array_filter($s['keputusan'], fn($v) => !is_null($v) && $v !== ''));
            }
            if (isset($s['action_items'])) {
                $s['action_items'] = array_values(array_filter($s['action_items'], fn($row) => !empty($row['task']) || !empty($row['pic']) || !empty($row['deadline'])));
            }
            if (isset($s['risiko_catatan'])) {
                $s['risiko_catatan'] = array_values(array_filter($s['risiko_catatan'], fn($v) => !is_null($v) && $v !== ''));
            }

            $validated['structured_summary'] = $s;
        }

        $notulensi->update($validated);

        if ($request->filled('nama_rapat') && $notulensi->meeting) {
            $notulensi->meeting->update(['nama_rapat' => $request->nama_rapat]);
        }

        return redirect()->route('admin.notulensis.show', $notulensi)
            ->with('success', 'Notulensi berhasil diperbarui.');
    }

    public function destroy(Notulensi $notulensi)
    {
        $notulensi->delete();

        return redirect()->route('admin.notulensis.index')
            ->with('success', 'Notulensi berhasil dihapus.');
    }
}
