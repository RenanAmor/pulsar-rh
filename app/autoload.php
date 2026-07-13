<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/app.php';

spl_autoload_register(function ($class) {

    $prefix = 'App\\';

    $baseDir = __DIR__ . '/';

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));

    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
        return;
    }

    if ($class === 'Database\\Database') {
        require_once __DIR__ . '/../database/Database.php';
    }
});