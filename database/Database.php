<?php

namespace Database;

use PDO;
use PDOException;

class Database
{
    public static function connect(): PDO
    {
        $env = parse_ini_file(__DIR__ . '/../.env');

        if (!$env) {
            throw new PDOException('.env não encontrado.');
        }

        return new PDO(
            "mysql:host={$env['DB_HOST']};dbname={$env['DB_DATABASE']};charset=utf8mb4",
            $env['DB_USERNAME'],
            $env['DB_PASSWORD'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
}