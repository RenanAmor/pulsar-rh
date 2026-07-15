<?php

namespace App\Database;

use PDO;
use Exception;

class Migration
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function run(string $directory): void
    {
        if (!is_dir($directory)) {
            throw new Exception("Diretório de migrations não encontrado.");
        }

        $files = glob($directory . DIRECTORY_SEPARATOR . '*.sql');

        sort($files);

        foreach ($files as $file) {

            if (basename($file) === 'RUN_ALL_MIGRATIONS.sql') {
                continue;
            }

            echo "Executando: " . basename($file) . PHP_EOL;

            $sql = file_get_contents($file);

            $this->db->exec($sql);
        }

        echo PHP_EOL;
        echo "====================================" . PHP_EOL;
        echo "Todas as migrations foram executadas." . PHP_EOL;
        echo "====================================" . PHP_EOL;
    }
}