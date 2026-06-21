<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LLMService
{
    public function ask(string $question, array $history = []): array
    {
        $provider = config('llm.provider', 'gemini');

        if ($provider === 'openai') {
            return $this->askOpenAI($question, $history);
        }

        return $this->askGemini($question, $history);
    }

    private function systemPrompt(): string
    {
        return "Kamu adalah AI Chatbot Asisten Virtual Kesehatan Mental MindHaven. "
            . "Jawab pertanyaan pengguna secara nyambung sesuai konteks percakapan sebelumnya. "
            . "Gunakan bahasa Indonesia yang ramah, natural, dan terasa seperti chat langsung. "
            . "Jangan terlalu pendek. Berikan jawaban jelas, rapi, dan mudah dipahami. "
            . "Jika pengguna bertanya penyebab, jelaskan beberapa pemicu umum. "
            . "Jika pengguna bertanya solusi, berikan langkah praktis. "
            . "Jangan membuat diagnosis pasti. Jangan menyebut pengguna pasti mengalami gangguan tertentu. "
            . "Jangan menggantikan psikolog atau psikiater. "
            . "Jika ada tanda bahaya seperti ingin bunuh diri, menyakiti diri, menyakiti orang lain, atau kondisi darurat, arahkan segera menghubungi keluarga terdekat, layanan darurat, atau profesional. "
            . "Akhiri dengan arahan ringan untuk konsultasi dengan psikolog MindHaven jika keluhan sering muncul, berat, atau mengganggu aktivitas.";
    }

    private function buildGeminiContents(string $question, array $history): array
    {
        $contents = [];

        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $this->systemPrompt()],
            ],
        ];

        $contents[] = [
            'role' => 'model',
            'parts' => [
                ['text' => 'Baik, saya akan menjawab sebagai asisten virtual MindHaven.'],
            ],
        ];

        foreach ($history as $item) {
            if (!empty($item['pertanyaan'])) {
                $contents[] = [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $item['pertanyaan']],
                    ],
                ];
            }

            if (!empty($item['jawaban'])) {
                $contents[] = [
                    'role' => 'model',
                    'parts' => [
                        ['text' => $item['jawaban']],
                    ],
                ];
            }
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $question],
            ],
        ];

        return $contents;
    }

    private function askGemini(string $question, array $history = []): array
    {
        $apiKey = config('llm.gemini.api_key');
        $model = config('llm.gemini.model');
        $baseUrl = rtrim(config('llm.gemini.url'), '/');

        if (!$apiKey) {
            return [
                'success' => false,
                'answer' => 'API key Gemini belum diatur di file .env.',
                'provider' => 'gemini',
                'model' => $model,
            ];
        }

        try {
            $response = Http::timeout(45)->post(
                "{$baseUrl}/{$model}:generateContent?key={$apiKey}",
                [
                    'contents' => $this->buildGeminiContents($question, $history),
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 1000,
                    ],
                ]
            );

            if (!$response->successful()) {
                Log::error('Gemini API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'answer' => 'Maaf, AI sedang tidak dapat merespons. Silakan coba lagi nanti.',
                    'provider' => 'gemini',
                    'model' => $model,
                ];
            }

            return [
                'success' => true,
                'answer' => data_get($response->json(), 'candidates.0.content.parts.0.text', 'Maaf, AI belum memberikan jawaban.'),
                'provider' => 'gemini',
                'model' => $model,
            ];
        } catch (\Throwable $e) {
            Log::error('Gemini Exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'answer' => 'Terjadi gangguan koneksi ke layanan AI.',
                'provider' => 'gemini',
                'model' => $model,
            ];
        }
    }

    private function askOpenAI(string $question, array $history = []): array
    {
        $apiKey = config('llm.openai.api_key');
        $model = config('llm.openai.model');
        $url = config('llm.openai.url');

        if (!$apiKey) {
            return [
                'success' => false,
                'answer' => 'API key OpenAI belum diatur di file .env.',
                'provider' => 'openai',
                'model' => $model,
            ];
        }

        $messages = [
            [
                'role' => 'system',
                'content' => $this->systemPrompt(),
            ],
        ];

        foreach ($history as $item) {
            if (!empty($item['pertanyaan'])) {
                $messages[] = [
                    'role' => 'user',
                    'content' => $item['pertanyaan'],
                ];
            }

            if (!empty($item['jawaban'])) {
                $messages[] = [
                    'role' => 'assistant',
                    'content' => $item['jawaban'],
                ];
            }
        }

        $messages[] = [
            'role' => 'user',
            'content' => $question,
        ];

        try {
            $response = Http::timeout(45)
                ->withToken($apiKey)
                ->post($url, [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.7,
                    'max_tokens' => 1000,
                ]);

            if (!$response->successful()) {
                Log::error('OpenAI API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'success' => false,
                    'answer' => 'Maaf, AI sedang tidak dapat merespons. Silakan coba lagi nanti.',
                    'provider' => 'openai',
                    'model' => $model,
                ];
            }

            return [
                'success' => true,
                'answer' => data_get($response->json(), 'choices.0.message.content', 'Maaf, AI belum memberikan jawaban.'),
                'provider' => 'openai',
                'model' => $model,
            ];
        } catch (\Throwable $e) {
            Log::error('OpenAI Exception', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'answer' => 'Terjadi gangguan koneksi ke layanan AI.',
                'provider' => 'openai',
                'model' => $model,
            ];
        }
    }
}