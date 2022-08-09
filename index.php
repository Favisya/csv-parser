<?php

require_once 'vendor/autoload.php';
require      'classLoader.php';

$app = FileHandlerFacade::getInstance();
$app->runFileHandler('5_input_data_2.csv');
