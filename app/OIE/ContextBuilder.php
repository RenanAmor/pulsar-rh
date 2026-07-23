<?php

namespace App\OIE;

class ContextBuilder
{
    /**
     * Monta o contexto organizacional estruturado consumido pelo OIE.
     *
     * Este é exatamente o objeto que futuramente será enviado a um
     * AIProvider — nenhuma chamada de IA acontece aqui ou em qualquer
     * ponto do OIE.
     */
    public function build(
        array $organization,
        array $indicators,
        array $history,
        array $patterns,
        array $risks,
        array $recommendations
    ): array {
        return [
            'generated_at'    => (new \DateTimeImmutable())->format(DATE_ATOM),
            'organization'    => $organization,
            'indicators'      => [
                'final_score'     => $indicators['final_score'] ?? null,
                'classification'  => $indicators['classification'] ?? null,
                'participation'   => $indicators['participation'] ?? null,
                'burnout_risk'    => $indicators['burnout_risk'] ?? null,
                'turnover_risk'   => $indicators['turnover_risk'] ?? null,
                'categories'      => $indicators['categories'] ?? [],
                'dimensions'      => $indicators['dimensions'] ?? [],
                'responses_count' => $indicators['responses_count'] ?? 0,
            ],
            'history'         => $history,
            'patterns'        => $patterns,
            'risks'           => $risks,
            'recommendations' => $recommendations,
        ];
    }
}
