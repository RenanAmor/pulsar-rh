<?php

namespace App\AI;

class PromptBuilder
{
    public function build(array $context): string
    {
        $organization = $context['organization'] ?? [];
        $indicators = $context['indicators'] ?? [];
        $patterns = $context['patterns'] ?? [];
        $risks = $context['risks'] ?? [];
        $recommendations = $context['recommendations'] ?? [];
        $history = $context['history'] ?? [];

        $lines = [];

        $lines[] = 'Você é um consultor de Psicologia Organizacional analisando os '
            . 'resultados de uma pesquisa de clima. Utilize exclusivamente os dados '
            . 'abaixo, produzidos pelo Motor de Indicadores e pelo Organizational '
            . 'Intelligence Engine (OIE) do Pulsar RH.';

        $lines[] = '';
        $lines[] = '## ESTRUTURA ORGANIZACIONAL';
        $lines[] = 'Empresa: ' . ($organization['company_name'] ?? 'N/A');
        $lines[] = 'Pesquisa: ' . ($organization['survey_title'] ?? 'N/A');

        $lines[] = '';
        $lines[] = '## INDICADORES';
        $lines[] = 'Índice Geral: ' . ($indicators['final_score'] ?? 'N/A');
        $lines[] = 'Classificação: ' . ($indicators['classification'] ?? 'N/A');
        $lines[] = 'Participação: ' . ($indicators['participation']['percentage'] ?? 'N/A') . '%';
        $lines[] = 'Risco de Burnout: ' . ($indicators['burnout_risk'] ?? 'N/A');

        foreach ($indicators['categories'] ?? [] as $category) {
            $lines[] = '- ' . $category['category'] . ': ' . ($category['average'] ?? 'N/A');
        }

        $lines[] = '';
        $lines[] = '## HISTÓRICO';
        $lines[] = count($history) . ' snapshot(s) anteriores disponíveis para esta empresa.';

        $lines[] = '';
        $lines[] = '## TENDÊNCIAS E PADRÕES';
        $lines[] = $patterns['has_previous'] ?? false
            ? ('Tendência geral: ' . ($patterns['overall'] ?? 'N/A'))
            : 'Sem pesquisa anterior para comparação de tendência.';

        $lines[] = '';
        $lines[] = '## RISCOS DETECTADOS PELO OIE';
        if (empty($risks)) {
            $lines[] = 'Nenhum risco detectado.';
        } else {
            foreach ($risks as $risk) {
                $lines[] = '- ' . $risk['label'] . ' (severidade: ' . $risk['severity'] . ')';
            }
        }

        $lines[] = '';
        $lines[] = '## RECOMENDAÇÕES PRODUZIDAS PELO OIE';
        if (empty($recommendations)) {
            $lines[] = 'Nenhuma recomendação produzida pelas regras de negócio.';
        } else {
            foreach ($recommendations as $recommendation) {
                $lines[] = '- ' . $recommendation['title'] . ': ' . $recommendation['reason'];
            }
        }

        $lines[] = '';
        $lines[] = '## INSTRUÇÃO';
        $lines[] = 'Responda exclusivamente em JSON válido, com as chaves: '
            . 'executive_summary (string), detailed_analysis (string), '
            . 'opportunities (lista de strings), risks (lista de strings), '
            . 'recommendations (lista de strings).';

        return implode("\n", $lines);
    }
}
