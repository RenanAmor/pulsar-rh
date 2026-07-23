<?php

namespace App\AI;

/**
 * Contrato desacoplado que qualquer provedor de IA deve implementar.
 * O OIE e o restante do Pulsar RH nunca dependem de um provedor
 * específico — apenas desta interface.
 */
interface AIProvider
{
    public function name(): string;

    /**
     * Indica se o provedor está configurado e pronto para uso (ex.:
     * possui uma chave de API válida). Deve ser barato/rápido de checar
     * e nunca deve lançar exceção.
     */
    public function isAvailable(): bool;

    /**
     * Envia o prompt (e o contexto estruturado, para provedores locais
     * que não dependem de rede) e retorna:
     * [
     *     'content' => string (resposta bruta do modelo),
     *     'model'   => string,
     *     'usage'   => array (tokens ou métricas equivalentes),
     * ]
     */
    public function send(string $prompt, array $context): array;
}
