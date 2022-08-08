<?php

require_once 'App/Exceptions/FileHandlerException.php';
require_once 'App/Exceptions/DataException.php';

require_once 'vendor/autoload.php';
require_once 'App/FileHandler.php';
require_once 'App/CsvHandler.php';
require_once 'App/DataFilter.php';
require_once 'App/InfoHandler.php';
require_once 'App/XlsxHandler.php';
require_once 'App/FileHandlerFactory.php';
require_once 'App/FileHandlerFacade.php';
require_once 'App/FileFormatAdapterInterface.php';
require_once 'App/InfoAdapter.php';
require_once 'App/TxtAdapter.php';
require_once 'App/TxtHandler.php';
require_once 'constants.php';


$app = FileHandlerFacade::getInstance();
$app->runFileHandler('5_input_data_2.csv');
