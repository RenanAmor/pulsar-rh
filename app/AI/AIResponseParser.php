<?php

namespace App\AI;

class AIResponseParser
{
    private const EXPECTED_KEYS = [
        'executive_summary',
        'detailed_analysis',
        'opportunities',
        'risks',
        'recommendations',
    ];

    private function stripCodeFences(string $raw): string
    {
        $trimmed = trim($raw);

        if (str_starts_with($trimmed, '```')) {
            $trimmed = preg_replace('/^```[a-zA-Z]*\n?/', '', $trimmed);
            $trimmed = preg_replace('/```$/', '', $trimmed);
        }

        return trim($trimmed);
    }

    /**
     * Converte a resposta bruta da IA (ou do NullProvider) em uma
     * estrutura interna, separando resumo executivo, análise detalhada,
     * oportunidades, riscos e recomendações. Nunca lança exceção: uma
     * resposta fora do formato esperado é tratada como fallback, nunca
     * como falha do sistema.
     */
    public function parse(?string $raw): array
    {
        if ($raw === null || trim($raw) === '') {
            return $this->emptyResult(true);
        }

        $decoded = json_decode($this->stripCodeFences($raw), true);

        if (!is_array($decoded)) {
            return $this->emptyResult(true, $raw);
        }

        return [
            'executive_summary' => (string) ($decoded['executive_summary'] ?? ''),
            'detailed_analysis' => (string) ($decoded['detailed_analysis'] ?? ''),
            'opportunities'     => $this->toStringList($decoded['opportunities'] ?? []),
            'risks'             => $this->toStringList($decoded['risks'] ?? []),
            'recommendations'   => $this->toStringList($decoded['recommendations'] ?? []),
            'parse_fallback'    => false,
        ];
    }

    private function toStringList($value): array
    {
        if (!is_array($value)) {
            return [];
        }

        return array_values(array_map('strval', $value));
    }

    private function emptyResult(bool $fallback, ?string $rawAsSummary = null): array
    {
        return [
            'executive_summary' => $rawAsSummary ?? '',
            'detailed_analysis' => '',
            'opportunities'     => [],
            'risks'             => [],
            'recommendations'   => [],
            'parse_fallback'    => $fallback,
        ];
    }
}
