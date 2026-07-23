<?php

namespace App\AI\Providers;

class ClaudeProvider extends AbstractApiProvider
{
    private const MODEL = 'claude-sonnet-5';
    private const ENDPOINT = 'https://api.anthropic.com/v1/messages';

    public function name(): string
    {
        return 'Claude';
    }

    public function isAvailable(): bool
    {
        return $this->envValue('ANTHROPIC_API_KEY') !== null;
    }

    public function send(string $prompt, array $context): array
    {
        $apiKey = $this->envValue('ANTHROPIC_API_KEY');

        $result = $this->postJson(
            self::ENDPOINT,
            [
                'Content-Type: application/json',
                'x-api-key: ' . $apiKey,
                'anthropic-version: 2023-06-01',
            ],
            [
                'model'      => self::MODEL,
                'max_tokens' => 2048,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]
        );

        if (isset($result['error'])) {
            return ['content' => null, 'model' => self::MODEL, 'usage' => [], 'error' => $result['error']];
        }

        $decoded = json_decode($result['body'], true);
        $content = $decoded['content'][0]['text'] ?? null;

        return [
            'content' => $content,
            'model'   => self::MODEL,
            'usage'   => $decoded['usage'] ?? [],
            'error'   => $content === null ? 'Resposta da Claude em formato inesperado.' : null,
        ];
    }
}
