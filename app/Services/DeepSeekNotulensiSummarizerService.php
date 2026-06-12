<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DeepSeekNotulensiSummarizerService
{
    public const PROMPT_VERSION = 'deepseek-notulensi-v1';

    public function summarize(string $transcript): array
    {
        $apiKey = config('deepseek.api_key');
        if (blank($apiKey)) {
            throw new \RuntimeException('DEEPSEEK_API_KEY belum diset.');
        }

        $model = (string) config('deepseek.model', 'deepseek-chat');
        $base = rtrim((string) config('deepseek.base_url'), '/');
        $timeout = (int) config('deepseek.request_timeout', 600);
        $retries = (int) config('deepseek.max_retries', 2);

        $system = <<<'PROMPT'
Kamu adalah sekretaris dan pembuat notulensi rapat profesional. Tugasmu adalah menganalisis transkrip percakapan rapat yang diberikan dalam bahasa Indonesia dan menyusun notulensi yang sangat rapi.
Keluarkan hasil analisis dalam format JSON murni tanpa membungkusnya dengan tag markdown seperti ```json atau tanda kutip tambahan lainnya. Respons kamu harus berupa string JSON valid yang dapat langsung diparse dengan json_decode di PHP.

Struktur JSON yang WAJIB kamu ikuti adalah:
{
  "ringkasan": "Ringkasan eksekutif jalannya rapat secara ringkas namun padat dan jelas (5-10 kalimat).",
  "topik_dibahas": [
    "Topik ke-1 yang dibahas...",
    "Topik ke-2..."
  ],
  "keputusan": [
    "Keputusan rapat ke-1...",
    "Keputusan rapat ke-2..."
  ],
  "action_items": [
    {
      "task": "Detail tugas yang harus dikerjakan",
      "pic": "Nama orang atau tim yang bertanggung jawab (isi '-' jika tidak disebutkan)",
      "deadline": "Batas waktu pengerjaan tugas (isi '-' jika tidak disebutkan)"
    }
  ],
  "risiko_catatan": [
    "Risiko, kendala, atau catatan penting tambahan ke-1...",
    "Risiko, kendala, atau catatan penting tambahan ke-2..."
  ]
}
PROMPT;

        $response = Http::withToken($apiKey)
            ->timeout($timeout)
            ->retry($retries, 2000, throw: false)
            ->post("{$base}/chat/completions", [
                'model' => $model,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $system],
                    ['role' => 'user', 'content' => $transcript],
                ],
            ]);

        if (! $response->successful()) {
            Log::warning('DeepSeek summarization failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            $response->throw();
        }

        $json = $response->json();
        $content = (string) data_get($json, 'choices.0.message.content', '');
        $usage = is_array($json['usage'] ?? null) ? $json['usage'] : [];

        $decoded = json_decode($content, true);
        if (! is_array($decoded)) {
            throw new \RuntimeException('Respons DeepSeek bukan JSON valid.');
        }

        return [
            'structured' => $decoded,
            'ringkasan' => (string) ($decoded['ringkasan'] ?? ''),
            'model' => $model,
            'usage' => $usage,
        ];
    }
}
