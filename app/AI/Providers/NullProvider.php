<?php

namespace App\AI\Providers;

use App\AI\AIProvider;

/**
 * Provedor de fallback: sempre disponível, nunca faz chamada externa e
 * nunca tem custo. Garante que o Pulsar RH continue funcionando
 * normalmente utilizando apenas o OIE quando nenhum provedor de IA
 * estiver configurado.
 */
class NullProvider implements AIProvider
{
    public function name(): string
    {
        return 'NullProvider';
    }

    public function isAvailable(): bool
    {
        return true;
    }

    public function send(string $prompt, array $context): array
    {
        $indicators = $context['indicators'] ?? [];
        $risks = $context['risks'] ?? [];
        $recommendations = $context['recommendations'] ?? [];

        $summary = sprintf(
            'Índice Geral %s (%s), participação de %s%%. %d risco(s) e %d recomendação(ões) identificados pelo OIE com base nas regras de negócio do Pulsar RH.',
            $indicators['final_score'] ?? 'N/A',
            $indicators['classification'] ?? 'N/A',
            $indicators['participation']['percentage'] ?? 'N/A',
            count($risks),
            count($recommendations)
        );

        $content = json_encode([
            'executive_summary' => $summary,
            'detailed_analysis' => 'Análise gerada automaticamente pelas regras de negócio do OIE, sem uso de Inteligência Artificial (nenhum provedor de IA está configurado).',
            'opportunities'     => array_map(fn (array $r) => $r['title'], $recommendations),
            'risks'             => array_map(fn (array $r) => $r['label'], $risks),
            'recommendations'   => array_map(fn (array $r) => $r['title'] . ': ' . $r['reason'], $recommendations),
        ], JSON_UNESCAPED_UNICODE);

        return [
            'content' => $content,
            'model'   => 'oie-rules-v1',
            'usage'   => ['prompt_chars' => strlen($prompt), 'estimated_cost' => 0.0],
        ];
    }
}
