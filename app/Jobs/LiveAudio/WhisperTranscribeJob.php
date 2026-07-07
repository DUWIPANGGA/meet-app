<?php

namespace App\Jobs\LiveAudio;

use App\Models\LiveAudio;
use App\Services\WhisperCppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class WhisperTranscribeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 1800;
    public int $backoff = 120;

    public function __construct(public int $liveAudioId)
    {
    }

    public function handle(WhisperCppService $whisper): void
    {
        $liveAudio = LiveAudio::query()->findOrFail($this->liveAudioId);
        $relative = $liveAudio->file_path;

        if (blank($relative)) {
            throw new \RuntimeException('Path audio untuk transkripsi kosong.');
        }

        $absolute = Storage::disk('public')->path($relative);
        if (! is_file($absolute)) {
            throw new \RuntimeException('File audio untuk transkripsi tidak ditemukan.');
        }

        Log::info('WhisperTranscribeJob: transcribing', ['live_audio_id' => $this->liveAudioId]);

        $transcriptText = $whisper->transcribeChunk($absolute);

        $liveAudio->update(['transcript' => $transcriptText]);

        Log::info('WhisperTranscribeJob: completed', ['live_audio_id' => $this->liveAudioId]);
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('WhisperTranscribeJob: failed after all retries', [
            'live_audio_id' => $this->liveAudioId,
            'error' => $exception?->getMessage(),
        ]);
    }

    public function retryUntil(): \DateTime
    {
        return now()->addMinutes(60);
    }
}
