<?php

require_once 'App/Exceptions/FileHandlerExceptions.php';
require_once 'App/Exceptions/DataExceptions.php';

require_once 'vendor/autoload.php';
require_once 'App/FileHandler.php';
require_once 'App/CsvHandler.php';
require_once 'App/DataFilter.php';
require_once 'App/InfoHandler.php';
require_once 'App/XlsxHandler.php';
require_once 'App/FileHandlerFactory.php';
require_once 'App/FileHandlerFacade.php';
require_once 'App/FileFormatAdapter.php';
require_once 'App/InfoAdapter.php';
require_once 'constants.php';

function runHandler(
    string $filePointer,
    string $directory   = 'output',
    string $fileFormat  = 'csv'
): void {
    $app = FileHandlerFacade::getInstance();
    $app->runFileHandler($filePointer, $directory, $fileFormat);
}

//runHandler('5_input_data_2.csv', 'out');