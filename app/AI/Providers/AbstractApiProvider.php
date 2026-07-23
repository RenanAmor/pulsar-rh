<?php

namespace App\AI\Providers;

use App\AI\AIProvider;

abstract class AbstractApiProvider implements AIProvider
{
    protected function envValue(string $key): ?string
    {
        $path = __DIR__ . '/../../../.env';

        if (!file_exists($path)) {
            return null;
        }

        $env = parse_ini_file($path);

        if (!$env || empty($env[$key])) {
            return null;
        }

        return $env[$key];
    }

    /**
     * Requisição HTTP simples via cURL (extensão nativa do PHP, sem
     * dependências novas). Nunca lança exceção: erros de rede/HTTP são
     * devolvidos como parte do array de retorno para que o AIManager
     * trate a falha com segurança (fallback), sem comprometer o sistema.
     */
    protected function postJson(string $url, array $headers, array $body): array
    {
        if (!function_exists('curl_init')) {
            return ['error' => 'Extensão cURL indisponível neste ambiente.'];
        }

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_POSTFIELDS     => json_encode($body),
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error !== '') {
            return ['error' => $error];
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            return ['error' => "HTTP {$httpCode}", 'body' => $response];
        }

        return ['body' => $response];
    }
}
