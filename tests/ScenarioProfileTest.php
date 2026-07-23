<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Laboratory/ScenarioProfile.php';
require_once __DIR__ . '/../app/Indicators/IndicatorCalculator.php';

use App\Laboratory\ScenarioProfile;
use App\Indicators\IndicatorCalculator;

$profile = new ScenarioProfile();
$calculator = new IndicatorCalculator();
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

// Todos os 10 cenários existem e têm rótulo
$expectedScenarios = [
    'saudavel', 'crescimento', 'alta_rotatividade', 'problemas_lideranca',
    'baixa_comunicacao', 'baixo_engajamento', 'alto_burnout',
    'conflitos_equipes', 'baixa_satisfacao', 'mudanca_organizacional',
];

check(
    'Existem exatamente 10 cenários definidos',
    count(ScenarioProfile::SCENARIOS) === 10,
    $failures
);

foreach ($expectedScenarios as $key) {
    check("Cenário '{$key}' está definido", array_key_exists($key, ScenarioProfile::SCENARIOS), $failures);
}

// Amostragem sempre respeita o intervalo alvo do cenário
for ($i = 0; $i < 50; $i++) {
    $sample = $profile->samplePercentage('alto_burnout', 'Burnout');
    if ($sample < 75 || $sample > 95) {
        $failures++;
        echo "FAIL: amostra de Burnout no cenário alto_burnout fora do intervalo [75,95]: {$sample}\n";
        break;
    }
}
echo "PASS: 50 amostras de Burnout (cenário alto_burnout) dentro do intervalo [75,95]\n";

for ($i = 0; $i < 50; $i++) {
    $sample = $profile->samplePercentage('problemas_lideranca', 'Liderança');
    if ($sample < 15 || $sample > 35) {
        $failures++;
        echo "FAIL: amostra de Liderança no cenário problemas_lideranca fora do intervalo [15,35]: {$sample}\n";
        break;
    }
}
echo "PASS: 50 amostras de Liderança (cenário problemas_lideranca) dentro do intervalo [15,35]\n";

// dimensão sem override cai no intervalo default do cenário
[$defaultMin, $defaultMax] = $profile->targetRangeFor('problemas_lideranca', 'Reconhecimento');
check(
    'Dimensão sem override usa o intervalo default do cenário',
    [$defaultMin, $defaultMax] === ScenarioProfile::SCENARIOS['problemas_lideranca']['default'],
    $failures
);

// denormalizeToScale é o inverso de IndicatorCalculator::normalizeAnswer
foreach ([[1, 5], [0, 10], [1, 7]] as [$min, $max]) {
    foreach ([0.0, 25.0, 50.0, 75.0, 100.0] as $percentage) {
        $rawValue = $profile->denormalizeToScale($percentage, $min, $max);

        if ($rawValue < $min || $rawValue > $max) {
            $failures++;
            echo "FAIL: denormalizeToScale({$percentage}, {$min}, {$max}) = {$rawValue} fora da escala\n";
        }
    }
}
echo "PASS: denormalizeToScale sempre produz valores dentro de scale_min/scale_max\n";

check(
    'denormalizeToScale(0, 1, 5) retorna o mínimo da escala',
    $profile->denormalizeToScale(0.0, 1, 5) === 1,
    $failures
);

check(
    'denormalizeToScale(100, 1, 5) retorna o máximo da escala',
    $profile->denormalizeToScale(100.0, 1, 5) === 5,
    $failures
);

// Round-trip: denormalizar e normalizar de volta deve aproximar o percentual original
$roundTrip = $calculator->normalizeAnswer([
    'answer_type' => 'Escala',
    'score'       => $profile->denormalizeToScale(100.0, 1, 5),
    'scale_min'   => 1,
    'scale_max'   => 5,
]);

check(
    'Round-trip denormalize(100) -> normalize() permanece em 100.0',
    $roundTrip === 100.0,
    $failures
);

// percentageToYesNo com 100% sempre retorna Sim, com 0% sempre Não
$allYes = true;
$allNo = true;

for ($i = 0; $i < 20; $i++) {
    if ($profile->percentageToYesNo(100.0) !== 'Sim') {
        $allYes = false;
    }
    if ($profile->percentageToYesNo(0.0) !== 'Não') {
        $allNo = false;
    }
}

check('percentageToYesNo(100) sempre retorna "Sim"', $allYes, $failures);
check('percentageToYesNo(0) sempre retorna "Não"', $allNo, $failures);

echo "\n";

if ($failures > 0) {
    echo "{$failures} teste(s) falharam.\n";
    exit(1);
}

echo "Todos os testes do ScenarioProfile passaram.\n";
exit(0);
