<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Services/ExecutiveDashboardPresenter.php';

use App\Services\ExecutiveDashboardPresenter;

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
        'organization'    => ['company_name' => 'Empresa Teste'],
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

$presenter = new ExecutiveDashboardPresenter();

// ---------- Erro repassado sem modificação ----------

$errorResult = $presenter->present(['error' => 'Pesquisa não encontrada.']);
check('Contexto de erro é repassado como está, sem montar o resto do dashboard', $errorResult === ['error' => 'Pesquisa não encontrada.'], $failures);

// ---------- Classificação nunca é inventada (passthrough do Motor de Indicadores) ----------

foreach (['Excelente' => 'tier-excelente', 'Saudável' => 'tier-saudavel', 'Atenção' => 'tier-atencao', 'Crítico' => 'tier-critico'] as $classification => $expectedClass) {
    $result = $presenter->present(baseContext(['indicators' => ['classification' => $classification]]));
    check("Classificação '{$classification}' é repassada verbatim", $result['health']['classification'] === $classification, $failures);
    check("Classificação '{$classification}' mapeia para a classe CSS '{$expectedClass}'", $result['health']['tierClass'] === $expectedClass, $failures);
}

$unknownTier = $presenter->present(baseContext(['indicators' => ['classification' => 'Algo Inesperado']]));
check('Classificação desconhecida cai no tierClass padrão', $unknownTier['health']['tierClass'] === 'tier-desconhecido', $failures);

// ---------- Organização saudável: sem alertas, sem recomendação crítica ----------

$healthy = $presenter->present(baseContext());
check('Organização saudável não gera nenhum alerta', $healthy['topAlerts'] === [], $failures);
check('Organização saudável não tem prioridade crítica do dia', $healthy['priorityOfDay']['type'] === 'none', $failures);
check('Organização saudável não tem recomendação principal', $healthy['mainRecommendation']['title'] === null, $failures);
check('Organização saudável recebe mensagem de recomendação amigável', $healthy['mainRecommendation']['message'] !== null, $failures);

// ---------- Alertas: ordenados por severidade, mapeados para manchete, limitados a 4 ----------

$risks = [
    ['key' => 'baixa_comunicacao', 'label' => 'Baixa Comunicação', 'severity' => 'Média', 'value' => 45],
    ['key' => 'risco_burnout', 'label' => 'Risco de Burnout', 'severity' => 'Crítica', 'value' => 85],
    ['key' => 'baixa_lideranca', 'label' => 'Baixa Liderança', 'severity' => 'Alta', 'value' => 35],
    ['key' => 'baixo_engajamento', 'label' => 'Baixo Engajamento', 'severity' => 'Média', 'value' => 42],
    ['key' => 'conflitos_entre_equipes', 'label' => 'Conflitos entre Equipes', 'severity' => 'Média', 'value' => 40],
];

$withRisks = $presenter->present(baseContext(['risks' => $risks]));

check('Alertas são limitados a 4 itens', count($withRisks['topAlerts']) === 4, $failures);
check('Alerta Crítica vem primeiro', $withRisks['topAlerts'][0]['headline'] === 'Burnout elevado.', $failures);
check('Alerta Alta vem em segundo', $withRisks['topAlerts'][1]['headline'] === 'Liderança crítica.', $failures);
check('Empate de severidade preserva a ordem original (baixa_comunicacao antes de baixo_engajamento)', $withRisks['topAlerts'][2]['headline'] === 'Comunicação em queda.', $failures);
check('Empate de severidade preserva a ordem original (baixo_engajamento em 4º)', $withRisks['topAlerts'][3]['headline'] === 'Engajamento em queda.', $failures);
check('5º risco (Média, último em ordem de empate) é descartado pelo limite de 4', !in_array('Conflitos entre equipes identificados.', array_column($withRisks['topAlerts'], 'headline'), true), $failures);

// ---------- Prioridade do dia prioriza o risco mais severo sobre recomendações ----------

$withRisksAndRecs = $presenter->present(baseContext([
    'risks'           => $risks,
    'recommendations' => [['title' => 'Revisão Organizacional', 'reason' => 'Índice geral crítico.', 'priority' => 'Crítica']],
]));
check('Prioridade do dia usa o risco mais severo quando há risco', $withRisksAndRecs['priorityOfDay']['type'] === 'risk', $failures);
check('Prioridade do dia aponta o Burnout como manchete', $withRisksAndRecs['priorityOfDay']['title'] === 'Burnout elevado.', $failures);

$withOnlyRecs = $presenter->present(baseContext([
    'recommendations' => [
        ['title' => 'Fortalecer Comunicação', 'reason' => 'Comunicação abaixo do esperado.', 'priority' => 'Média'],
        ['title' => 'Revisão Organizacional', 'reason' => 'Índice geral crítico.', 'priority' => 'Crítica'],
    ],
]));
check('Sem riscos, prioridade do dia cai para a recomendação mais prioritária', $withOnlyRecs['priorityOfDay']['type'] === 'recommendation', $failures);
check('Prioridade do dia escolhe a recomendação Crítica mesmo não sendo a primeira do array', $withOnlyRecs['priorityOfDay']['title'] === 'Revisão Organizacional', $failures);
check('Recomendação principal também escolhe a de maior prioridade', $withOnlyRecs['mainRecommendation']['title'] === 'Revisão Organizacional', $failures);

// ---------- O que mudou: filtra Estável, ignora final_score, ordena por magnitude, limita a 4 ----------

$patterns = [
    'has_previous' => true,
    'dimensions'   => [
        'leadership'    => ['current' => 62, 'previous' => 50, 'delta' => 12, 'classification' => 'Melhora'],
        'communication' => ['current' => 42, 'previous' => 50, 'delta' => -8, 'classification' => 'Piora'],
        'culture'       => ['current' => 40, 'previous' => 60, 'delta' => -20, 'classification' => 'Mudança Brusca (Piora)'],
        'burnout_risk'  => ['current' => 70, 'previous' => 60, 'delta' => 10, 'classification' => 'Piora'],
        'engagement'    => ['current' => 61, 'previous' => 60, 'delta' => 1, 'classification' => 'Estável'],
        'final_score'   => ['current' => 55, 'previous' => 60, 'delta' => -5, 'classification' => 'Piora'],
    ],
    'overall' => 'Piora',
];

$changes = $presenter->present(baseContext(['patterns' => $patterns]))['changes'];

check('Mudanças ignoram o Índice Geral (já exibido no card principal)', !in_array('Índice Geral', array_column($changes['items'], 'label'), true), $failures);
check('Mudanças ignoram dimensões Estáveis', !in_array('Engajamento', array_column($changes['items'], 'label'), true), $failures);
check('Mudanças são limitadas a 4 itens', count($changes['items']) === 4, $failures);
check('Maior variação (Clima Organizacional, -20) aparece primeiro', $changes['items'][0]['label'] === 'Clima Organizacional', $failures);
check('Seta para baixo em variação negativa', $changes['items'][0]['arrow'] === '↓', $failures);
check('Seta para cima em variação positiva', $changes['items'][1]['arrow'] === '↑', $failures);

// ---------- Sem pesquisa anterior: mensagem calma, nunca uma lista vazia fabricada ----------

$noPrevious = $presenter->present(baseContext());
check('Sem pesquisa anterior, changes não tem histórico prévio', $noPrevious['changes']['hasPrevious'] === false, $failures);
check('Sem pesquisa anterior, mensagem explicativa é exibida', $noPrevious['changes']['message'] === 'Ainda não há pesquisa anterior para comparação.', $failures);

// ---------- Sem mudanças relevantes (tudo Estável) ----------

$allStable = $presenter->present(baseContext(['patterns' => [
    'has_previous' => true,
    'dimensions'   => ['leadership' => ['current' => 80, 'previous' => 79, 'delta' => 1, 'classification' => 'Estável']],
    'overall'      => 'Estável',
]]));
check('Todas as dimensões estáveis geram mensagem própria (não uma lista vazia)', $allStable['changes']['message'] === 'Nenhuma mudança significativa desde a última pesquisa.', $failures);

echo "\n";

if ($failures > 0) {
    echo "{$failures} teste(s) falharam.\n";
    exit(1);
}

echo "Todos os testes do Dashboard Executivo passaram.\n";
exit(0);
