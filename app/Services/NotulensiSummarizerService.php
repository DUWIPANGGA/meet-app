<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Log;

class NotulensiSummarizerService
{
    public const PROMPT_VERSION = 'deepseek-notulensi-v2';

    private DeepSeekNotulensiSummarizerService $deepseek;

    public function __construct(
        DeepSeekNotulensiSummarizerService $deepseek,
    ) {
        $this->deepseek = $deepseek;
    }

    public function summarize(string $transcript, ?string $meetingName = null): array
    {
        $context = $meetingName ? "Rapat: {$meetingName}\n\n" : '';
        $fullTranscript = $context . $transcript;

        try {
            Log::info('NotulensiSummarizer: trying DeepSeek');
            return $this->deepseek->summarize($fullTranscript);
        } catch (\Throwable $e) {
            Log::error('NotulensiSummarizer: DeepSeek failed', [
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException(
                'DeepSeek summarization gagal: ' . $e->getMessage()
            );
        }
    }
}
