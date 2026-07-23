<?php

namespace App\AI;

class AIValidator
{
    private const MAX_SUMMARY_LENGTH = 4000;
    private const MAX_ANALYSIS_LENGTH = 20000;

    public function validate(array $parsed): array
    {
        $errors = [];

        $summary = trim($parsed['executive_summary'] ?? '');

        if ($summary === '') {
            $errors[] = 'Resumo executivo (executive_summary) está vazio.';
        }

        if (strlen($summary) > self::MAX_SUMMARY_LENGTH) {
            $errors[] = 'Resumo executivo excede o tamanho máximo permitido.';
        }

        if (strlen($parsed['detailed_analysis'] ?? '') > self::MAX_ANALYSIS_LENGTH) {
            $errors[] = 'Análise detalhada excede o tamanho máximo permitido.';
        }

        foreach (['opportunities', 'risks', 'recommendations'] as $field) {
            if (!isset($parsed[$field]) || !is_array($parsed[$field])) {
                $errors[] = "Campo obrigatório '{$field}' ausente ou em formato inválido.";
            }
        }

        if (!empty($parsed['parse_fallback'])) {
            $errors[] = 'A resposta não pôde ser interpretada como JSON estruturado.';
        }

        return [
            'valid'  => empty($errors),
            'errors' => $errors,
        ];
    }
}
