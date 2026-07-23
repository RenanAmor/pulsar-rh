<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Indicators/IndicatorEngine.php';
require_once __DIR__ . '/../app/OIE/PatternDetector.php';
require_once __DIR__ . '/../app/OIE/RecommendationBuilder.php';
require_once __DIR__ . '/../app/OIE/OrganizationalAnalyzer.php';
require_once __DIR__ . '/../app/OIE/ContextBuilder.php';

use App\OIE\PatternDetector;
use App\OIE\RecommendationBuilder;
use App\OIE\OrganizationalAnalyzer;
use App\OIE\ContextBuilder;

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

function indicatorResult(array $categoryAverages, array $dimensionAverages, ?float $finalScore, float $burnoutRisk): array
{
    $categories = [];
    foreach ($categoryAverages as $name => $average) {
        $categories[] = ['category' => $name, 'average' => $average, 'responses_count' => 10];
    }

    $dimensions = [];
    foreach ($dimensionAverages as $name => $average) {
        $dimensions[] = ['dimension' => $name, 'average' => $average, 'responses_count' => 10];
    }

    return [
        'categories'      => $categories,
        'dimensions'      => $dimensions,
        'final_score'     => $finalScore,
        'burnout_risk'    => $burnoutRisk,
        'turnover_risk'   => 0.0,
        'participation'   => ['eligible_employees' => 10, 'responded_employees' => 10, 'percentage' => 100.0],
        'responses_count' => 80,
    ];
}

// ---------- PatternDetector ----------

$detector = new PatternDetector();

$current = ['leadership' => 80, 'final_score' => 80, 'burnout_risk' => 20, 'turnover_risk' => 10];
$previous = ['leadership' => 78, 'final_score' => 78, 'burnout_risk' => 22, 'turnover_risk' => 12];
$comparison = $detector->compare($current, $previous);

check('compare() com previous null retorna has_previous=false', $detector->compare($current, null)['has_previous'] === false, $failures);
check('Delta pequeno (2 pontos) é classificado como Estável', $comparison['dimensions']['leadership']['classification'] === 'Estável', $failures);

$improved = $detector->compare(['final_score' => 90], ['final_score' => 70]);
check('Delta de +20 é Mudança Brusca (Melhora)', $improved['dimensions']['final_score']['classification'] === 'Mudança Brusca (Melhora)', $failures);

$worsened = $detector->compare(['final_score' => 60], ['final_score' => 80]);
check('Delta de -20 é Mudança Brusca (Piora)', $worsened['dimensions']['final_score']['classification'] === 'Mudança Brusca (Piora)', $failures);

$mildImprove = $detector->compare(['final_score' => 60], ['final_score' => 55]);
check('Delta de +5 é Melhora', $mildImprove['dimensions']['final_score']['classification'] === 'Melhora', $failures);

// burnout_risk é invertido: um AUMENTO no risco é Piora, não Melhora
// (delta de 10 pontos fica entre STABLE_THRESHOLD e ABRUPT_THRESHOLD, ou seja, mudança "leve")
$burnoutUp = $detector->compare(['burnout_risk' => 60], ['burnout_risk' => 50]);
check('Aumento de burnout_risk é classificado como Piora (coluna invertida)', $burnoutUp['dimensions']['burnout_risk']['classification'] === 'Piora', $failures);

$burnoutDown = $detector->compare(['burnout_risk' => 50], ['burnout_risk' => 60]);
check('Queda de burnout_risk é classificada como Melhora (coluna invertida)', $burnoutDown['dimensions']['burnout_risk']['classification'] === 'Melhora', $failures);

$burnoutUpAbrupt = $detector->compare(['burnout_risk' => 80], ['burnout_risk' => 50]);
check('Aumento abrupto (+30) de burnout_risk é Mudança Brusca (Piora)', $burnoutUpAbrupt['dimensions']['burnout_risk']['classification'] === 'Mudança Brusca (Piora)', $failures);

check(
    'Série crescente de final_score é detectada como Crescente',
    $detector->detectTrendSeries([['final_score' => 50], ['final_score' => 60], ['final_score' => 70]], 'final_score') === 'Crescente',
    $failures
);

check(
    'Série decrescente de final_score é detectada como Decrescente',
    $detector->detectTrendSeries([['final_score' => 70], ['final_score' => 60], ['final_score' => 50]], 'final_score') === 'Decrescente',
    $failures
);

check(
    'Série com um único snapshot retorna Histórico Insuficiente',
    $detector->detectTrendSeries([['final_score' => 70]], 'final_score') === 'Histórico Insuficiente',
    $failures
);

// ---------- RecommendationBuilder ----------

$recommender = new RecommendationBuilder();

$healthyRecs = $recommender->build(
    ['Liderança' => 90, 'Comunicação' => 90, 'Engajamento' => 90, 'Desenvolvimento' => 90],
    90.0,
    10.0
);
check('Empresa saudável não gera recomendações', empty($healthyRecs), $failures);

$leadershipRecs = $recommender->build(['Liderança' => 20], 70.0, 10.0);
$titles = array_column($leadershipRecs, 'title');
check('Liderança baixa gera recomendação de Treinamento de Liderança', in_array('Treinamento de Liderança', $titles, true), $failures);

$burnoutRecs = $recommender->build([], 70.0, 90.0);
check('Burnout alto gera recomendação de Acompanhamento Psicológico', in_array('Acompanhamento Psicológico', array_column($burnoutRecs, 'title'), true), $failures);

$criticalRecs = $recommender->build([], 20.0, 10.0);
check('Índice geral crítico gera recomendação de Revisão Organizacional', in_array('Revisão Organizacional', array_column($criticalRecs, 'title'), true), $failures);

// ---------- OrganizationalAnalyzer::detectRisks (pure, sem DB) ----------

$analyzer = new OrganizationalAnalyzer(new class extends \App\Indicators\IndicatorEngine {
    public function __construct() {}
}, $detector);

$healthyResult = indicatorResult(
    ['Liderança' => 85, 'Comunicação' => 85, 'Engajamento' => 85, 'Cultura' => 85, 'Bem-estar' => 85, 'Reconhecimento' => 85],
    ['Satisfação' => 85, 'Pertencimento' => 85, 'Trabalho em Equipe' => 85, 'Segurança Psicológica' => 85],
    85.0,
    15.0
);
check('Empresa saudável não gera nenhum risco', empty($analyzer->detectRisks($healthyResult)), $failures);

$leadershipRiskResult = indicatorResult(['Liderança' => 25], [], 70.0, 20.0);
$risks = $analyzer->detectRisks($leadershipRiskResult);
check('Liderança baixa é detectada como risco baixa_lideranca', in_array('baixa_lideranca', array_column($risks, 'key'), true), $failures);

$burnoutRiskResult = indicatorResult([], [], 70.0, 90.0);
$risks = $analyzer->detectRisks($burnoutRiskResult);
check('Burnout alto é detectado como risco risco_burnout', in_array('risco_burnout', array_column($risks, 'key'), true), $failures);

$turnoverRiskResult = indicatorResult(
    ['Engajamento' => 10, 'Reconhecimento' => 10],
    ['Satisfação' => 10, 'Pertencimento' => 10],
    40.0,
    20.0
);
$risks = $analyzer->detectRisks($turnoverRiskResult);
check('Engajamento/satisfação/reconhecimento/pertencimento baixos geram risco_turnover', in_array('risco_turnover', array_column($risks, 'key'), true), $failures);

// Riscos comparativos (precisam de snapshot anterior)
$currentWithDrop = indicatorResult(['Cultura' => 50], ['Satisfação' => 50], 60.0, 20.0);
$previousBeforeDrop = indicatorResult(['Cultura' => 70], ['Satisfação' => 70], 70.0, 20.0);

$risksWithoutPrevious = $analyzer->detectRisks($currentWithDrop, null);
check('Sem snapshot anterior, queda_de_clima não é avaliada', !in_array('queda_de_clima', array_column($risksWithoutPrevious, 'key'), true), $failures);

$risksWithPrevious = $analyzer->detectRisks($currentWithDrop, $previousBeforeDrop);
check('Queda de 20 pontos em Cultura é detectada como queda_de_clima', in_array('queda_de_clima', array_column($risksWithPrevious, 'key'), true), $failures);
check('Queda de 20 pontos em Satisfação é detectada como perda_de_satisfacao', in_array('perda_de_satisfacao', array_column($risksWithPrevious, 'key'), true), $failures);

// ---------- ContextBuilder ----------

$contextBuilder = new ContextBuilder();
$context = $contextBuilder->build(
    ['company_id' => 1, 'company_name' => 'Empresa Teste'],
    indicatorResult(['Liderança' => 80], [], 80.0, 20.0),
    [],
    ['has_previous' => false],
    [],
    []
);

check('ContextBuilder produz as chaves esperadas', count(array_diff(
    ['generated_at', 'organization', 'indicators', 'history', 'patterns', 'risks', 'recommendations'],
    array_keys($context)
)) === 0, $failures);

check('ContextBuilder nunca inclui qualquer referência a IA', !preg_match('/\b(ia|ai|gpt|openai|claude_api|gemini)\b/i', json_encode($context)), $failures);

echo "\n";

if ($failures > 0) {
    echo "{$failures} teste(s) falharam.\n";
    exit(1);
}

echo "Todos os testes do OIE passaram.\n";
exit(0);
