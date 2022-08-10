#!/usr/bin/php
<?php

require_once 'App/AnotherStuff/constants.php';
require_once 'vendor/autoload.php';
require      'classLoader.php';

use App\Handler\FileHandlerFacade;
use App\ProjectException\FileHandlerException;

$fileFromConsole = $argv[1] ?? null;

try {
    if ($fileFromConsole === null) {
        throw new FileHandlerException('File does not entered!');
    }

    $fileFormatFromConsole = $argv[2] ?? 'csv';

    $app = FileHandlerFacade::getInstance();
    $app->runFileHandler($fileFromConsole, 'output', $fileFormatFromConsole);
} catch (FileHandlerException $e) {
    echo $e->getMessage() . PHP_EOL;
}
