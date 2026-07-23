<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Services/IntelligenceCenterPresenter.php';

use App\Services\IntelligenceCenterPresenter;

$failures = 0;

function check(string $label, bool $condition, int &$failures): void
{
    if ($condition) {
        echo "PASS: {$label}\n";
        return;
    }

    $failures++;
    echo "FAIL: {$label}\n";
}

function baseContext(array $overrides = []): array
{
    return array_replace_recursive([
        'generated_at'    => '2026-07-23T09:00:00-03:00',
        'organization'    => ['company_name' => 'Empresa Teste', 'survey_title' => 'Pesquisa Q3'],
        'indicators'      => [
            'final_score'    => 85.0,
            'classification' => 'Excelente',
            'participation'  => ['percentage' => 90.0],
        ],
        'history'         => [],
        'patterns'        => ['has_previous' => false, 'dimensions' => [], 'overall' => 'Sem histórico anterior para comparação'],
        'risks'           => [],
        'recommendations' => [],
    ], $overrides);
}

$presenter = new IntelligenceCenterPresenter();

// ---------- Erro repassado sem modificação ----------

$errorResult = $presenter->present(['error' => 'Pesquisa não encontrada.']);
check('Contexto de erro é repassado como está', $errorResult === ['error' => 'Pesquisa não encontrada.'], $failures);

// ---------- Situação Atual: linguagem executiva, nunca inventa classificação ----------

$healthy = $presenter->present(baseContext());
check('Situação atual repassa a classificação verbatim', $healthy['situation']['classification'] === 'Excelente', $failures);
check('Situação atual mapeia para a classe CSS correta', $healthy['situation']['tierClass'] === 'tier-excelente', $failures);
check('Sem alertas, resumo executivo comunica organização saudável', str_contains($healthy['situation']['summary'], 'Nenhum alerta crítico'), $failures);
check('Meta traz o nome da empresa', $healthy['meta']['companyName'] === 'Empresa Teste', $failures);

$risks = [
    ['key' => 'baixa_comunicacao', 'label' => 'Baixa Comunicação', 'severity' => 'Média', 'value' => 45],
    ['key' => 'risco_burnout', 'label' => 'Risco de Burnout', 'severity' => 'Crítica', 'value' => 85],
];

$withRisks = $presenter->present(baseContext(['risks' => $risks]));
check('Resumo executivo cita a quantidade de pontos de atenção', str_contains($withRisks['situation']['summary'], '2 ponto(s) de atenção'), $failures);
check('Resumo executivo aponta a severidade mais urgente', str_contains($withRisks['situation']['summary'], 'Crítica'), $failures);

// ---------- Alertas: nunca exibe lista vazia, cada item traz gravidade, setor, tendência e impacto ----------

$noAlerts = $presenter->present(baseContext());
check('Sem riscos, alertas vêm marcados como vazios', $noAlerts['alerts']['empty'] === true, $failures);
check('Sem riscos, nenhum item de alerta é fabricado', $noAlerts['alerts']['items'] === [], $failures);

$withAlerts = $presenter->present(baseContext(['risks' => $risks]));
check('Alertas não estão vazios quando há riscos', $withAlerts['alerts']['empty'] === false, $failures);
check('Alerta mais severo (Crítica) vem primeiro', $withAlerts['alerts']['items'][0]['severity'] === 'Crítica', $failures);
check('Alerta traz o setor afetado', $withAlerts['alerts']['items'][0]['sector'] === 'Bem-estar', $failures);
check('Alerta traz um texto de impacto', $withAlerts['alerts']['items'][0]['impact'] !== '', $failures);
check('Alerta sem dado comparativo assume tendência neutra', $withAlerts['alerts']['items'][1]['trend'] === 'Sem dado comparativo', $failures);

// ---------- Tendência do alerta usa a comparação já calculada pelo OIE ----------

$patternsWithHistory = [
    'has_previous' => true,
    'dimensions'   => [
        'burnout_risk' => ['current' => 85, 'previous' => 60, 'delta' => 25, 'classification' => 'Mudança Brusca (Piora)'],
    ],
    'overall' => 'Piora',
];

$withTrend = $presenter->present(baseContext(['risks' => $risks, 'patterns' => $patternsWithHistory]));
$burnoutAlert = current(array_filter($withTrend['alerts']['items'], fn (array $item): bool => $item['sector'] === 'Bem-estar'));
check('Tendência do alerta reflete a classificação de padrão do OIE', $burnoutAlert['trend'] === 'Mudança Brusca (Piora)', $failures);

// ---------- Recomendações: nunca exibe lista vazia, cada item traz objetivo, motivo, prioridade e impacto ----------

$noRecs = $presenter->present(baseContext());
check('Sem recomendações, a seção vem marcada como vazia', $noRecs['recommendations']['empty'] === true, $failures);

$recommendations = [
    ['title' => 'Fortalecer Comunicação', 'reason' => 'Indicador abaixo do esperado.', 'priority' => 'Média'],
    ['title' => 'Revisão Organizacional', 'reason' => 'Índice geral crítico.', 'priority' => 'Crítica'],
];

$withRecs = $presenter->present(baseContext(['recommendations' => $recommendations]));
check('Recomendações não estão vazias quando há recomendações', $withRecs['recommendations']['empty'] === false, $failures);
check('Recomendação mais prioritária (Crítica) vem primeiro', $withRecs['recommendations']['items'][0]['priority'] === 'Crítica', $failures);
check('Recomendação usa o título como objetivo', $withRecs['recommendations']['items'][0]['objective'] === 'Revisão Organizacional', $failures);
check('Recomendação traz um texto de impacto esperado', $withRecs['recommendations']['items'][0]['impact'] !== '', $failures);

// ---------- Mudanças: filtra Estável, ignora Índice Geral ----------

$patterns = [
    'has_previous' => true,
    'dimensions'   => [
        'leadership'    => ['current' => 62, 'previous' => 50, 'delta' => 12, 'classification' => 'Melhora'],
        'communication' => ['current' => 42, 'previous' => 50, 'delta' => -8, 'classification' => 'Piora'],
        'engagement'    => ['current' => 61, 'previous' => 60, 'delta' => 1, 'classification' => 'Estável'],
        'final_score'   => ['current' => 55, 'previous' => 60, 'delta' => -5, 'classification' => 'Piora'],
    ],
    'overall' => 'Piora',
];

$changes = $presenter->present(baseContext(['patterns' => $patterns]))['changes'];
check('Mudanças ignoram o Índice Geral', !in_array('Índice Geral', array_column($changes['items'], 'label'), true), $failures);
check('Mudanças ignoram dimensões Estáveis', !in_array('Engajamento', array_column($changes['items'], 'label'), true), $failures);
check('Maior variação aparece primeiro', $changes['items'][0]['label'] === 'Liderança', $failures);
check('Cada mudança traz a intensidade', $changes['items'][0]['intensity'] === 'Melhora', $failures);

$noPrevious = $presenter->present(baseContext())['changes'];
check('Sem pesquisa anterior, mensagem explicativa é exibida', $noPrevious['message'] === 'Ainda não há pesquisa anterior para comparação.', $failures);

// ---------- Evolução: usa exclusivamente o histórico produzido pelo Motor de Indicadores ----------

$noHistory = $presenter->present(baseContext())['evolution'];
check('Sem histórico, evolução não fabrica série', $noHistory['hasHistory'] === false, $failures);

$history = [
    ['created_at' => '2026-01-10 10:00:00', 'final_score' => 60.0, 'classification' => 'Atenção'],
    ['created_at' => '2026-04-10 10:00:00', 'final_score' => 70.0, 'classification' => 'Saudável'],
    ['created_at' => '2026-07-10 10:00:00', 'final_score' => 85.0, 'classification' => 'Excelente'],
];

$withHistory = $presenter->present(baseContext(['history' => $history]))['evolution'];
check('Com histórico, a série é montada com uma entrada por snapshot', count($withHistory['series']) === 3, $failures);
check('Primeira entrada não tem seta de variação', $withHistory['series'][0]['arrow'] === null, $failures);
check('Entrada seguinte mostra seta de alta quando o índice sobe', $withHistory['series'][1]['arrow'] === '↑', $failures);
check('Tendência geral é calculada a partir do primeiro e do último snapshot', $withHistory['trend'] === 'Crescente', $failures);

echo "\n";

if ($failures > 0) {
    echo "{$failures} teste(s) falharam.\n";
    exit(1);
}

echo "Todos os testes do Centro de Inteligência Organizacional passaram.\n";
exit(0);
