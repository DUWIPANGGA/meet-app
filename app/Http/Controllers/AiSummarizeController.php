<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\NotulensiSummarizerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AiSummarizeController extends Controller
{
    public function summarize(Request $request, NotulensiSummarizerService $summarizer)
    {
        $validated = $request->validate([
            'text' => 'required|string',
        ]);

        try {
            $result = $summarizer->summarize($validated['text']);

            return response()->json([
                'status' => 'success',
                'data' => $result['structured'],
            ]);
        } catch (\Throwable $e) {
            Log::error('AiSummarize failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Gagal membuat notulensi: ' . $e->getMessage(),
            ], 500);
        }
    }
}
