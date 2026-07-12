<?php

spl_autoload_register(function ($class) {

    $prefixes = [

        'App\\'      => __DIR__ . '/',
        'Database\\' => __DIR__ . '/../database/',

    ];

    foreach ($prefixes as $prefix => $baseDir) {

        if (strpos($class, $prefix) !== 0) {
            continue;
        }

        $relative = substr($class, strlen($prefix));

        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';

        if (file_exists($file)) {
            require_once $file;
            return;
        }

    }

});