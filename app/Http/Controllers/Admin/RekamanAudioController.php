<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RekamanAudio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RekamanAudioController extends Controller
{
    public function index(Request $request)
    {
        $query = RekamanAudio::with('meeting', 'user')->where('tipe_rekaman', 'audio');

        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            $query->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhereHas('meeting', fn ($m) => $m->where('dibuat_oleh', auth()->id()));
            });
        }

        $rekamans = $query->latest()->paginate(20)->withQueryString();

        return view('admin.rekaman-audio.index', compact('rekamans'));
    }

    public function videoIndex(Request $request)
    {
        $query = RekamanAudio::with('meeting', 'user', 'accessUsers')->where('tipe_rekaman', 'video');

        if (!auth()->user()->hasAnyRole(['super_admin', 'admin'])) {
            $query->where(function ($q) {
                $q->where('user_id', auth()->id())
                  ->orWhereHas('meeting', fn ($m) => $m->where('dibuat_oleh', auth()->id()));
            });
        }

        $rekamans = $query->latest()->paginate(20)->withQueryString();

        return view('admin.rekaman-video.index', compact('rekamans'));
    }

    public function stream(RekamanAudio $rekaman)
    {
        if ($rekaman->tipe_rekaman !== 'video') {
            abort(404);
        }

        $disk = Storage::disk('local');
        $path = $rekaman->raw_recording_path;

        if (!$disk->exists($path)) {
            abort(404);
        }

        $fullPath = $disk->path($path);
        $mime = $rekaman->mime_type ?: 'video/webm';

        return response()->file($fullPath, [
            'Content-Type' => $mime,
        ]);
    }

    public function play(RekamanAudio $rekaman)
    {
        $disk = Storage::disk('local');
        $path = $rekaman->extracted_audio_path ?? $rekaman->raw_recording_path;

        abort_if(!$path || !$disk->exists($path), 404, 'File audio tidak ditemukan.');

        $mime = $rekaman->mime_type ?: 'audio/mpeg';
        $fullPath = $disk->path($path);

        return response()->file($fullPath, ['Content-Type' => $mime]);
    }

    public function download(RekamanAudio $rekaman)
    {
        if ($rekaman->tipe_rekaman !== 'video') {
            abort(404);
        }

        $disk = Storage::disk('local');
        $path = $rekaman->raw_recording_path;

        if (!$disk->exists($path)) {
            abort(404);
        }

        $fullPath = $disk->path($path);
        $mime = $rekaman->mime_type ?: 'video/webm';
        $ext = pathinfo($path, PATHINFO_EXTENSION) ?: 'webm';
        $filename = 'rekaman-' . ($rekaman->meeting_id ?? 'unknown') . '-' . $rekaman->id . '.' . $ext;

        return response()->download($fullPath, $filename, [
            'Content-Type' => $mime,
        ]);
    }

    public function updateAccess(Request $request, RekamanAudio $rekaman): JsonResponse
    {
        $request->validate([
            'akses_rekaman' => 'required|in:pemilik,semua_orang,pilih_user',
            'akses_user_ids' => 'nullable|array',
            'akses_user_ids.*' => 'exists:users,id',
        ]);

        $rekaman->update(['akses_rekaman' => $request->akses_rekaman]);

        if ($request->akses_rekaman === 'pilih_user' && $request->filled('akses_user_ids')) {
            $rekaman->accessUsers()->sync($request->akses_user_ids);
        } else {
            $rekaman->accessUsers()->sync([]);
        }

        return response()->json(['message' => 'Akses rekaman berhasil diperbarui.']);
    }

    public function destroy(RekamanAudio $rekaman, Request $request)
    {
        $disk = Storage::disk('local');
        if ($rekaman->raw_recording_path && $disk->exists($rekaman->raw_recording_path)) {
            $disk->delete($rekaman->raw_recording_path);
        }

        $rekaman->delete();

        $redirectRoute = $rekaman->tipe_rekaman === 'video'
            ? 'admin.rekaman-video.index'
            : 'admin.rekaman-audio.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Rekaman berhasil dihapus.');
    }
}
