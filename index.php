#!/usr/bin/php
<?php

require_once 'vendor/autoload.php';
require      'classLoader.php';

$fileFormat = 'csv';
$fileFormatFromConsole = $argv[2];
if ($argv[2] !== null) {
    $fileFormat = $fileFormatFromConsole;
}

$app = FileHandlerFacade::getInstance();
$app->runFileHandler($argv[1], 'output', $fileFormat);
