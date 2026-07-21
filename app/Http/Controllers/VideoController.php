<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingParticipant;
use App\Models\Notulensi;
use App\Models\RekamanAudio;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $meetingIds = MeetingParticipant::where('user_id', $userId)
            ->pluck('meeting_id')
            ->merge(
                Meeting::where('dibuat_oleh', $userId)->pluck('id')
            )
            // ->merge(
            //     Meeting::where('akses_meeting', 'semua_orang')->pluck('id')
            // )
            ->merge(
                Notulensi::where(function ($q) use ($userId) {
                    $q->where('akses_notulensi', 'all_users')
                        ->orWhereHas('accessUsers', function ($auq) use ($userId) {
                            $auq->where('user_id', $userId);
                        });
                })
                    ->whereNotNull('meeting_id')
                    ->pluck('meeting_id')
            )
            ->unique();

        $videos = RekamanAudio::where('tipe_rekaman', 'video')
            ->where(function ($q) use ($meetingIds, $userId) {
                $q->whereIn('meeting_id', $meetingIds)
                    ->orWhere('akses_rekaman', 'semua_orang')
                    ->orWhereHas('accessUsers', function ($auq) use ($userId) {
                        $auq->where('user_id', $userId);
                    });
            })
            ->with('meeting')
            ->latest()
            ->paginate(20);

        return view('video.index', compact('videos'));
    }

    public function show(RekamanAudio $rekaman)
    {
        $this->authorizeAccess($rekaman);

        if ($rekaman->tipe_rekaman !== 'video') {
            abort(404);
        }

        $rekaman->load('meeting.notulensi');

        return view('video.show', compact('rekaman'));
    }

    public function stream(RekamanAudio $rekaman)
    {
        $this->authorizeAccess($rekaman);

        $disk = Storage::disk('local');
        $path = $rekaman->raw_recording_path;

        if (! $disk->exists($path)) {
            abort(404);
        }

        $fullPath = $disk->path($path);
        $mime = $rekaman->mime_type ?: 'video/webm';

        return response()->file($fullPath, [
            'Content-Type' => $mime,
        ]);
    }

    public function download(RekamanAudio $rekaman)
    {
        $this->authorizeAccess($rekaman);

        $disk = Storage::disk('local');
        $path = $rekaman->raw_recording_path;

        if (! $disk->exists($path)) {
            abort(404);
        }

        $fullPath = $disk->path($path);
        $mime = $rekaman->mime_type ?: 'video/webm';
        $ext = pathinfo($path, PATHINFO_EXTENSION) ?: 'webm';
        $filename = 'rekaman-'.($rekaman->meeting_id ?? 'unknown').'-'.$rekaman->id.'.'.$ext;

        return response()->download($fullPath, $filename, [
            'Content-Type' => $mime,
        ]);
    }

    public function destroy(RekamanAudio $rekaman)
    {
        $this->authorizeAccess($rekaman);

        $disk = Storage::disk('local');
        if ($rekaman->raw_recording_path && $disk->exists($rekaman->raw_recording_path)) {
            $disk->delete($rekaman->raw_recording_path);
        }

        $rekaman->delete();

        return redirect()->route('video.index')->with('success', 'Video berhasil dihapus.');
    }

    private function authorizeAccess(RekamanAudio $rekaman)
    {
        $user = auth()->user();

        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return;
        }

        // Direct rekaman-level access
        $hasDirectAccess = $rekaman->akses_rekaman === 'semua_orang'
            || $rekaman->accessUsers()->where('user_id', $user->id)->exists();

        if ($hasDirectAccess) {
            return;
        }

        $meeting = $rekaman->meeting;

        if (! $meeting) {
            abort(403, 'Anda tidak memiliki akses ke rekaman ini.');
        }

        $isCreator = $meeting->dibuat_oleh === $user->id;
        $isParticipant = MeetingParticipant::where('meeting_id', $meeting->id)
            ->where('user_id', $user->id)
            ->exists();
        $isPublic = $meeting->akses_meeting === 'semua_orang';
        $hasNotulensiAccess = $meeting->notulensi && (
            $meeting->notulensi->akses_notulensi === 'all_users'
            || $meeting->notulensi->accessUsers()->where('user_id', $user->id)->exists()
        );

        if (! $isCreator && ! $isParticipant && ! $isPublic && ! $hasNotulensiAccess) {
            abort(403, 'Anda tidak memiliki akses ke rekaman ini.');
        }
    }
}
