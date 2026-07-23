<?php

namespace App\AI;

class ContextSerializer
{
    private const SCHEMA_VERSION = '1.0';

    /**
     * Normaliza o contexto produzido pelo OIE em uma estrutura estável e
     * versionada. Este é exatamente o objeto que futuramente será enviado
     * a um AIProvider — nenhuma chamada de IA acontece aqui.
     */
    public function toArray(array $context): array
    {
        return [
            'schema_version'  => self::SCHEMA_VERSION,
            'generated_at'    => $context['generated_at'] ?? null,
            'organization'    => $context['organization'] ?? [],
            'indicators'      => $context['indicators'] ?? [],
            'history'         => $context['history'] ?? [],
            'patterns'        => $context['patterns'] ?? [],
            'risks'           => $context['risks'] ?? [],
            'recommendations' => $context['recommendations'] ?? [],
        ];
    }

    public function serialize(array $context): string
    {
        return json_encode(
            $this->toArray($context),
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        );
    }
}
