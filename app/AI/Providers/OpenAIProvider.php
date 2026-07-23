<?php

namespace App\AI\Providers;

class OpenAIProvider extends AbstractApiProvider
{
    private const MODEL = 'gpt-4o-mini';
    private const ENDPOINT = 'https://api.openai.com/v1/chat/completions';

    public function name(): string
    {
        return 'OpenAI';
    }

    public function isAvailable(): bool
    {
        return $this->envValue('OPENAI_API_KEY') !== null;
    }

    public function send(string $prompt, array $context): array
    {
        $apiKey = $this->envValue('OPENAI_API_KEY');

        $result = $this->postJson(
            self::ENDPOINT,
            [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
            ],
            [
                'model'    => self::MODEL,
                'messages' => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]
        );

        if (isset($result['error'])) {
            return ['content' => null, 'model' => self::MODEL, 'usage' => [], 'error' => $result['error']];
        }

        $decoded = json_decode($result['body'], true);
        $content = $decoded['choices'][0]['message']['content'] ?? null;

        return [
            'content' => $content,
            'model'   => self::MODEL,
            'usage'   => $decoded['usage'] ?? [],
            'error'   => $content === null ? 'Resposta da OpenAI em formato inesperado.' : null,
        ];
    }
}
