<?php

namespace Database;

use PDO;

class Database
{
    public static function connect(): PDO
    {
        $host = 'localhost';
        $database = 'pulsar_rh';
        $user = 'root';
        $password = '';

        if (APP_ENV === 'production') {

            $host = 'SEU_HOST_MYSQL';
            $database = 'SEU_BANCO';
            $user = 'SEU_USUARIO';
            $password = 'SUA_SENHA';

        }

        return new PDO(
            "mysql:host={$host};dbname={$database};charset=utf8mb4",
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
}