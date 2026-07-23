<?php

namespace App\AI\Providers;

class GeminiProvider extends AbstractApiProvider
{
    private const MODEL = 'gemini-1.5-flash';

    public function name(): string
    {
        return 'Gemini';
    }

    public function isAvailable(): bool
    {
        return $this->envValue('GEMINI_API_KEY') !== null;
    }

    public function send(string $prompt, array $context): array
    {
        $apiKey = $this->envValue('GEMINI_API_KEY');
        $endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/'
            . self::MODEL . ':generateContent?key=' . $apiKey;

        $result = $this->postJson(
            $endpoint,
            ['Content-Type: application/json'],
            [
                'contents' => [
                    ['parts' => [['text' => $prompt]]],
                ],
            ]
        );

        if (isset($result['error'])) {
            return ['content' => null, 'model' => self::MODEL, 'usage' => [], 'error' => $result['error']];
        }

        $decoded = json_decode($result['body'], true);
        $content = $decoded['candidates'][0]['content']['parts'][0]['text'] ?? null;

        return [
            'content' => $content,
            'model'   => self::MODEL,
            'usage'   => $decoded['usageMetadata'] ?? [],
            'error'   => $content === null ? 'Resposta do Gemini em formato inesperado.' : null,
        ];
    }
}
