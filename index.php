<?php

require_once 'FileHandler.php';
require_once 'CsvHandler.php';
require_once 'CsvFilter.php';
require_once 'InfoHandler.php';

const FIRST_OUTPUT  = 'output_data_1.csv';
const SECOND_OUTPUT = 'output_data_2.csv';
const THIRD_OUTPUT  = 'output_data_3.csv';
const FOURTH_OUTPUT = 'output_data_4.csv';

function parseCsv(string $filePointer, string $directory): bool
{
    $csvObject  = new CsvFilter();
    $data       = $csvObject->readFile($filePointer);

    if ($data == false) {
       return false;
    }

    $parsedData = $csvObject->parse($data);
    $header     = $csvObject->getHeader($parsedData);

    array_shift($parsedData);

    $csvObject->initializationCsvFile($directory, FIRST_OUTPUT, $header);
    $filteredData = $csvObject->FilterDataByCountrySplit($parsedData);
    $csvObject->writeFile($directory, FIRST_OUTPUT, $filteredData);

    $csvObject->initializationCsvFile($directory, SECOND_OUTPUT, $header);
    $filteredData = $csvObject->FilterDataByCountry($parsedData, 'Russia');
    $csvObject->writeFile($directory, SECOND_OUTPUT, $filteredData);

    $csvObject->initializationCsvFile($directory, THIRD_OUTPUT, $header);
    $filteredData = $csvObject->FilterDataByLatOrLng($parsedData, 0);
    $csvObject->writeFile($directory, THIRD_OUTPUT, $filteredData);

    $populationField = ['populationFormatted' => 'population_formatted'];
    $header += $populationField;

    $csvObject->initializationCsvFile($directory, FOURTH_OUTPUT, $header);
    $filteredData = $csvObject->allDataPopulationFormatted($parsedData);
    $csvObject->writeFile($directory, FOURTH_OUTPUT, $filteredData);

    $infoObject = new InfoHandler();
    $data = $infoObject->getInfoAboutFiles($directory, $filePointer);
    $infoObject->writeFile($directory, 'infoAboutFiles.txt', $data);

    echo 'Ready!' . PHP_EOL;

    return true;
}
