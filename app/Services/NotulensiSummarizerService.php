<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;

class NotulensiSummarizerService
{
    public const PROMPT_VERSION = 'fallback-notulensi-v2';

    private DeepSeekNotulensiSummarizerService $deepseek;
    private GeminiNotulensiSummarizerService $gemini;

    public function __construct(
        DeepSeekNotulensiSummarizerService $deepseek,
        GeminiNotulensiSummarizerService $gemini
    ) {
        $this->deepseek = $deepseek;
        $this->gemini = $gemini;
    }

    public function summarize(string $transcript, ?string $meetingName = null): array
    {
        $context = $meetingName ? "Rapat: {$meetingName}\n\n" : '';
        $fullTranscript = $context . $transcript;

        $errors = [];

        try {
            Log::info('NotulensiSummarizer: trying DeepSeek');
            return $this->deepseek->summarize($fullTranscript);
        } catch (\Throwable $e) {
            Log::warning('NotulensiSummarizer: DeepSeek failed, falling back to Gemini', [
                'error' => $e->getMessage(),
            ]);
            $errors[] = 'DeepSeek: ' . $e->getMessage();
        }

        try {
            Log::info('NotulensiSummarizer: trying Gemini (free)');
            return $this->gemini->summarize($fullTranscript);
        } catch (\Throwable $e) {
            Log::error('NotulensiSummarizer: Gemini also failed', [
                'error' => $e->getMessage(),
            ]);
            $errors[] = 'Gemini: ' . $e->getMessage();
        }

        throw new \RuntimeException(
            'Semua provider summarization gagal: ' . implode(' | ', $errors)
        );
    }
}
