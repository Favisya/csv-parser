<?php

spl_autoload_register(function ($class) {
    $file = $class . '.php';
    $file = str_replace('\\', '/', $file);

    $prefix = 'App\\';

    $baseDir = __DIR__ . '/App/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
