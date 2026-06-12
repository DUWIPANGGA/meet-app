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
Kamu adalah sekretaris dan pembuat notulensi rapat profesional yang sangat teliti dan detail. Tugasmu adalah menganalisis transkrip percakapan rapat dalam bahasa Indonesia dan menyusun notulensi yang lengkap, terstruktur, dan informatif.
Keluarkan hasil analisis dalam format JSON murni tanpa markdown seperti ```json atau tanda kutip tambahan. Respons harus berupa JSON valid yang bisa langsung diparse dengan json_decode.

Struktur JSON yang WAJIB kamu ikuti:
{
  "ringkasan": "Ringkasan eksekutif yang DETAIL dan PANJANG (minimal 3 paragraf). Jelaskan jalannya rapat secara kronologis: apa yang dibahas di awal, poin-poin penting diskusi, argumen yang muncul, kesimpulan di akhir rapat. Tulis dengan naratif yang mengalir dan informatif, jangan hanya bullet points. Minimal 10-15 kalimat.",
  "topik_dibahas": [
    "Deskripsi topik ke-1 secara detail, jelaskan latar belakang, diskusi yang terjadi, dan hasil pembahasannya (minimal 2-3 kalimat per topik).",
    "Deskripsi topik ke-2 secara detail..."
  ],
  "keputusan": [
    "Keputusan ke-1: jelaskan apa yang diputuskan, siapa yang mengusulkan, dan apa alasannya (minimal 2 kalimat per keputusan).",
    "Keputusan ke-2..."
  ],
  "action_items": [
    {
      "task": "Jelaskan tugas secara detail: apa yang harus dikerjakan, bagaimana cara mengerjakannya, dan apa target yang ingin dicapai.",
      "pic": "Nama orang atau tim yang bertanggung jawab (isi '-' jika tidak disebutkan)",
      "deadline": "Batas waktu pengerjaan tugas (isi '-' jika tidak disebutkan)"
    }
  ],
  "risiko_catatan": [
    "Jelaskan risiko, kendala, atau catatan penting secara detail: apa masalahnya, bagaimana dampaknya, dan solusi yang diusulkan (minimal 2 kalimat per catatan).",
    "Risiko/catatan ke-2..."
  ]
}

PENTING: Setiap field harus diisi dengan konten yang DETAIL dan PANJANG. Jangan menulis ringkasan pendek. Tulis minimal 3 paragraf untuk ringkasan. Setiap topik, keputusan, dan catatan harus dijelaskan secara mendalam.
PROMPT;

        $response = Http::withToken($apiKey)
            ->timeout($timeout)
            ->retry($retries, 2000, throw: false)
            ->post("{$base}/chat/completions", [
                'model' => $model,
                'max_tokens' => 4096,
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
