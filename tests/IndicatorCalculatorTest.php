<?php

declare(strict_types=1);

require_once __DIR__ . '/../app/Indicators/IndicatorCalculator.php';

use App\Indicators\IndicatorCalculator;

$calculator = new IndicatorCalculator();
$failures = 0;

function check(string $label, $expected, $actual, int &$failures): void
{
    if ($expected === $actual) {
        echo "PASS: {$label}\n";
        return;
    }

    $failures++;
    echo "FAIL: {$label} (esperado " . var_export($expected, true) . ', obtido ' . var_export($actual, true) . ")\n";
}

// normalizeAnswer - Escala dentro da faixa
check(
    'Escala 3 em 1-5 normaliza para 50.0',
    50.0,
    $calculator->normalizeAnswer(['answer_type' => 'Escala', 'score' => 3, 'scale_min' => 1, 'scale_max' => 5]),
    $failures
);

check(
    'Escala no mínimo normaliza para 0.0',
    0.0,
    $calculator->normalizeAnswer(['answer_type' => 'Escala', 'score' => 1, 'scale_min' => 1, 'scale_max' => 5]),
    $failures
);

check(
    'Escala no máximo normaliza para 100.0',
    100.0,
    $calculator->normalizeAnswer(['answer_type' => 'Escala', 'score' => 5, 'scale_min' => 1, 'scale_max' => 5]),
    $failures
);

check(
    'Escala com score nulo retorna null',
    null,
    $calculator->normalizeAnswer(['answer_type' => 'Escala', 'score' => null, 'scale_min' => 1, 'scale_max' => 5]),
    $failures
);

// normalizeAnswer - SimNão
check(
    'SimNão "Sim" normaliza para 100.0',
    100.0,
    $calculator->normalizeAnswer(['answer_type' => 'SimNão', 'answer_text' => 'Sim']),
    $failures
);

check(
    'SimNão "Não" normaliza para 0.0',
    0.0,
    $calculator->normalizeAnswer(['answer_type' => 'SimNão', 'answer_text' => 'Não']),
    $failures
);

// normalizeAnswer - Texto e Múltipla Escolha não são quantificáveis
check(
    'Texto não é quantificável (retorna null)',
    null,
    $calculator->normalizeAnswer(['answer_type' => 'Texto', 'answer_text' => 'qualquer coisa']),
    $failures
);

check(
    'Múltipla Escolha não é quantificável (retorna null)',
    null,
    $calculator->normalizeAnswer(['answer_type' => 'Múltipla Escolha', 'answer_text' => 'Opção A']),
    $failures
);

// average
check(
    'Média simples de [100.0, 0.0, 50.0] é 50.0',
    50.0,
    $calculator->average([100.0, 0.0, 50.0]),
    $failures
);

check(
    'Média ignora valores nulos',
    100.0,
    $calculator->average([100.0, null, null]),
    $failures
);

check(
    'Média de lista vazia retorna null',
    null,
    $calculator->average([]),
    $failures
);

check(
    'Média de lista só com nulos retorna null',
    null,
    $calculator->average([null, null]),
    $failures
);

// weightedAverage
check(
    'Média ponderada favorece pergunta de maior peso',
    75.0,
    $calculator->weightedAverage([
        ['answer_type' => 'Escala', 'score' => 5, 'scale_min' => 1, 'scale_max' => 5, 'weight' => 3],
        ['answer_type' => 'Escala', 'score' => 1, 'scale_min' => 1, 'scale_max' => 5, 'weight' => 1],
    ]),
    $failures
);

check(
    'Média ponderada ignora respostas não quantificáveis',
    100.0,
    $calculator->weightedAverage([
        ['answer_type' => 'Escala', 'score' => 5, 'scale_min' => 1, 'scale_max' => 5, 'weight' => 1],
        ['answer_type' => 'Texto', 'answer_text' => 'irrelevante', 'weight' => 1],
    ]),
    $failures
);

// participation
check(
    'Participação de 5 em 10 é 50.0%',
    50.0,
    $calculator->participation(5, 10),
    $failures
);

check(
    'Participação com zero elegíveis é 0.0%',
    0.0,
    $calculator->participation(0, 0),
    $failures
);

check(
    'Participação nunca ultrapassa 100% mesmo com mais respostas que elegíveis',
    100.0,
    $calculator->participation(12, 10),
    $failures
);

// classify
check('Classifica 85 como Excelente', 'Excelente', $calculator->classify(85.0), $failures);
check('Classifica 65 como Saudável', 'Saudável', $calculator->classify(65.0), $failures);
check('Classifica 45 como Atenção', 'Atenção', $calculator->classify(45.0), $failures);
check('Classifica 20 como Crítico', 'Crítico', $calculator->classify(20.0), $failures);
check('Classifica null como Crítico', 'Crítico', $calculator->classify(null), $failures);

echo "\n";

if ($failures > 0) {
    echo "{$failures} teste(s) falharam.\n";
    exit(1);
}

echo "Todos os testes do IndicatorCalculator passaram.\n";
exit(0);
