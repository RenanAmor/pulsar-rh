<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Services/ExecutiveAssistantPresenter.php';

use App\Services\ExecutiveAssistantPresenter;

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

$presenter = new ExecutiveAssistantPresenter();

// ---------- Erro repassado sem modificação ----------

$errorResult = $presenter->present(['error' => 'Pesquisa não encontrada.']);
check('Contexto de erro é repassado como está, sem montar o resto do assistente', $errorResult === ['error' => 'Pesquisa não encontrada.'], $failures);

// ---------- Mensagem executiva: organização saudável, sem histórico anterior ----------

$healthy = $presenter->present(baseContext());
check('Mensagem executiva menciona que a organização está em excelente momento', str_contains($healthy['executiveMessage'], 'em excelente momento'), $failures);
check('Sem pesquisa anterior, mensagem executiva informa que é a primeira análise', str_contains($healthy['executiveMessage'], 'primeira análise disponível'), $failures);
check('Sem pesquisa anterior, resumo indica que é a primeira análise', $healthy['summary']['evolution'] === 'Esta é a primeira análise disponível para esta organização.', $failures);
check('Organização saudável não tem assuntos prioritários', $healthy['priorityTopics'] === [], $failures);
check('Organização saudável não tem ações recomendadas', $healthy['recommendedActions'] === [], $failures);
check('Sem riscos, perguntas estratégicas caem no conjunto genérico', count($healthy['strategicQuestions']) === 2, $failures);

// ---------- Mensagem executiva: mudanças desde a última análise ----------

$patternsWithChanges = [
    'has_previous' => true,
    'dimensions'   => [
        'leadership'    => ['current' => 62, 'previous' => 50, 'delta' => 12, 'classification' => 'Melhora'],
        'communication' => ['current' => 42, 'previous' => 50, 'delta' => -8, 'classification' => 'Piora'],
        'engagement'    => ['current' => 61, 'previous' => 60, 'delta' => 1, 'classification' => 'Estável'],
        'final_score'   => ['current' => 55, 'previous' => 60, 'delta' => -5, 'classification' => 'Piora'],
    ],
    'overall' => 'Piora',
];

$withChanges = $presenter->present(baseContext(['patterns' => $patternsWithChanges]));
check('Duas mudanças relevantes são contadas na mensagem executiva', str_contains($withChanges['executiveMessage'], '2 mudanças importantes'), $failures);
check('Índice Geral não é contado como mudança', !str_contains($withChanges['executiveMessage'], '3 mudanças'), $failures);
check('Evolução relata melhora e piora', $withChanges['summary']['evolution'] === 'Desde a última análise, 1 indicador(es) melhoraram e 1 indicador(es) pioraram.', $failures);

// ---------- Nenhuma mudança relevante (tudo estável) ----------

$noChanges = $presenter->present(baseContext(['patterns' => [
    'has_previous' => true,
    'dimensions'   => ['leadership' => ['current' => 80, 'previous' => 79, 'delta' => 1, 'classification' => 'Estável']],
    'overall'      => 'Estável',
]]));
check('Nenhuma mudança relevante é relatada na mensagem executiva', str_contains($noChanges['executiveMessage'], 'Nenhuma mudança relevante'), $failures);
check('Evolução relata estabilidade quando nada mudou', $noChanges['summary']['evolution'] === 'Os indicadores permanecem estáveis desde a última análise.', $failures);

// ---------- Tendência a partir do histórico ----------

$insufficientHistory = $presenter->present(baseContext(['history' => [['final_score' => 70]]]));
check('Histórico insuficiente gera mensagem própria de tendência', $insufficientHistory['summary']['trend'] === 'Ainda não há histórico suficiente para identificar uma tendência.', $failures);

$growingHistory = $presenter->present(baseContext(['history' => [['final_score' => 60], ['final_score' => 75]]]));
check('Crescimento no histórico gera tendência de crescimento', $growingHistory['summary']['trend'] === 'A tendência é de crescimento nos últimos períodos.', $failures);

$decliningHistory = $presenter->present(baseContext(['history' => [['final_score' => 75], ['final_score' => 60]]]));
check('Queda no histórico gera tendência de queda com alerta', $decliningHistory['summary']['trend'] === 'A tendência é de queda nos últimos períodos — recomenda-se atenção.', $failures);

$stableHistory = $presenter->present(baseContext(['history' => [['final_score' => 70], ['final_score' => 71]]]));
check('Variação pequena no histórico gera tendência de estabilidade', $stableHistory['summary']['trend'] === 'A tendência é de estabilidade nos últimos períodos.', $failures);

// ---------- Assuntos prioritários: apenas temas relevantes, ordenados por severidade ----------

$risks = [
    ['key' => 'baixa_comunicacao', 'label' => 'Baixa Comunicação', 'severity' => 'Média', 'value' => 45],
    ['key' => 'risco_burnout', 'label' => 'Risco de Burnout', 'severity' => 'Crítica', 'value' => 85],
    ['key' => 'baixa_lideranca', 'label' => 'Baixa Liderança', 'severity' => 'Alta', 'value' => 35],
];

$withRisks = $presenter->present(baseContext(['risks' => $risks]));
check('Assuntos prioritários vêm ordenados por severidade (Crítica primeiro)', $withRisks['priorityTopics'][0]['theme'] === 'Burnout', $failures);
check('Segundo assunto prioritário é Liderança (Alta)', $withRisks['priorityTopics'][1]['theme'] === 'Liderança', $failures);
check('Terceiro assunto prioritário é Comunicação (Média)', $withRisks['priorityTopics'][2]['theme'] === 'Comunicação', $failures);
check('Nenhum tema fora dos riscos identificados aparece (ex.: Turnover)', !in_array('Turnover', array_column($withRisks['priorityTopics'], 'theme'), true), $failures);

// ---------- Ações recomendadas: ordenadas por prioridade ----------

$recommendations = [
    ['title' => 'Fortalecer Comunicação', 'reason' => 'Comunicação abaixo do esperado.', 'priority' => 'Média'],
    ['title' => 'Revisão Organizacional', 'reason' => 'Índice geral crítico.', 'priority' => 'Crítica'],
];

$withRecs = $presenter->present(baseContext(['recommendations' => $recommendations]));
check('Ação de maior prioridade vem primeiro mesmo não sendo a primeira do array', $withRecs['recommendedActions'][0]['title'] === 'Revisão Organizacional', $failures);
check('Ação de menor prioridade vem em seguida', $withRecs['recommendedActions'][1]['title'] === 'Fortalecer Comunicação', $failures);

// ---------- Perguntas estratégicas: derivadas dos riscos, limitadas a 3, sem duplicatas ----------

$withRisks3 = $presenter->present(baseContext(['risks' => [
    ['key' => 'risco_burnout', 'label' => 'Risco de Burnout', 'severity' => 'Crítica', 'value' => 85],
    ['key' => 'baixa_lideranca', 'label' => 'Baixa Liderança', 'severity' => 'Alta', 'value' => 35],
    ['key' => 'baixa_comunicacao', 'label' => 'Baixa Comunicação', 'severity' => 'Média', 'value' => 45],
    ['key' => 'risco_turnover', 'label' => 'Risco de Turnover', 'severity' => 'Alta', 'value' => 65],
]]));
check('Perguntas estratégicas são limitadas a 3', count($withRisks3['strategicQuestions']) === 3, $failures);
check('Primeira pergunta corresponde ao risco mais severo (burnout)', $withRisks3['strategicQuestions'][0] === 'As equipes têm relatado sobrecarga de trabalho?', $failures);

echo "\n";

if ($failures > 0) {
    echo "{$failures} teste(s) falharam.\n";
    exit(1);
}

echo "Todos os testes do Assistente Executivo passaram.\n";
exit(0);
