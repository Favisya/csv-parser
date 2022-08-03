<?php

require_once 'FileHandler.php';
require_once 'CsvHandler.php';
require_once 'DataFilter.php';
require_once 'InfoHandler.php';

const FIRST_OUTPUT  = 'output_data_1.csv';
const SECOND_OUTPUT = 'output_data_2.csv';
const THIRD_OUTPUT  = 'output_data_3.csv';
const FOURTH_OUTPUT = 'output_data_4.csv';

function parseCsv(string $filePointer, string $directory): bool
{
    $csvObject  = new DataFilter();
    $data       = $csvObject->readFile($filePointer);

    if ($data == false) {
       return false;
    }

    $parsedData = $csvObject->parse($data);

    $filteredData = $csvObject->FilterDataByCountrySplit($parsedData, 1);
    $csvObject->writeFile($directory, FIRST_OUTPUT, $filteredData);

    $filteredData = $csvObject->FilterDataByCountry($parsedData, 'Russia');
    $csvObject->writeFile($directory, SECOND_OUTPUT, $filteredData);

    $filteredData = $csvObject->filterDataByLatOrLng($parsedData, 0);
    $csvObject->writeFile($directory, THIRD_OUTPUT, $filteredData);

    $populationField  = ['populationFormatted' => 'population_formatted'];
    $filteredData = $csvObject->getAllDataPopForm($parsedData);
    $filteredData[0] += $populationField;
    unset($filteredData[1]);
    $csvObject->writeFile($directory, FOURTH_OUTPUT, $filteredData);

    $infoObject = new InfoHandler();
    $data = $infoObject->getInfoAboutFiles($directory, $filePointer);
    $infoObject->writeFile($directory, 'infoAboutFiles.txt', $data);

    echo 'Ready!' . PHP_EOL;

    return true;
}
