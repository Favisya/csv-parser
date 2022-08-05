<?php

require_once 'vendor/autoload.php';
require_once 'App/FileHandler.php';
require_once 'App/CsvHandler.php';
require_once 'App/DataFilter.php';
require_once 'App/InfoHandler.php';
require_once 'App/XlsxHandler.php';
require_once 'App/FileHandlerFactory.php';
require_once 'App/FileHandlerFacade.php';
require_once 'App/Singleton.php';
require_once 'App/Adapter.php';
require_once 'constants.php';

function runHandler(
    string $filePointer,
    string $directory   = 'output',
    string $fileFormat  = 'csv'
): void {
    $facade = Singleton::getInstance();

    $facade->runFileHandler($filePointer, $directory, $fileFormat);
}
