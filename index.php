<?php

require_once 'App/FileHandler.php';
require_once 'App/CsvHandler.php';
require_once 'App/DataFilter.php';
require_once 'App/InfoHandler.php';
require_once 'App/XlsxHandler.php';

const FIRST_OUTPUT  = 'output_data_1';
const SECOND_OUTPUT = 'output_data_2';
const THIRD_OUTPUT  = 'output_data_3';
const FOURTH_OUTPUT = 'output_data_4';

function writeType(
    string $outFileName,
    string $directory,
    string $fileFormat,
    array  $data
): bool {
    $csvObject    = new CsvHandler();
    $xlsxObject   = new XlsxHandler();

    if ($fileFormat === 'csv') {
        $csvObject->writeFile($directory, $outFileName . '.' . 'csv', $data);
    } elseif ($fileFormat === 'xlsx') {
        $xlsxObject->writeFile($directory, $outFileName. '.' . 'xlsx', $data);
    } else {
        return false;
    }

    return true;
}

function readType(string $file): array
{
    $csvObject    = new CsvHandler();
    $xlsxObject   = new XlsxHandler();
    $resultData   = [];

    $explodeFileName = explode('.', $file);
    if ($explodeFileName[1] === 'csv') {
        $resultData = $csvObject->parse($csvObject->readFile($file));
    } elseif ($explodeFileName[1] === 'xlsx') {
        $resultData = $xlsxObject->readFile($file);
    }

    return $resultData;
}

function handler(
    string $filePointer,
    string $directory,
    string $fileFormat = 'csv'
): bool {
    if ($fileFormat !== 'csv' && $fileFormat !== 'xlsx') {
        echo'Incorrect file format!' . PHP_EOL;
        return false;
    }

    $csvObject    = new CsvHandler();
    $xlsxObject   = new XlsxHandler();
    $filterObject = new DataFilter();

    $parsedData = readType($filePointer);

    if ((bool) $parsedData == false) {
       return false;
    }

    $filteredData = $filterObject->filterDataByCountrySplit($parsedData, 1);
    writeType(FIRST_OUTPUT, $directory, $fileFormat, $filteredData);

    $filteredData = $filterObject->filterDataByCountry($parsedData, 'Russia');
    writeType(SECOND_OUTPUT, $directory, $fileFormat, $filteredData);

    $filteredData = $filterObject->filterDataByLatOrLng($parsedData, 0);
    writeType(THIRD_OUTPUT, $directory, $fileFormat, $filteredData);

    $populationField  = ['populationFormatted' => 'population_formatted'];
    $filteredData = $filterObject->getAllDataPopForm($parsedData);
    $filteredData[0] += $populationField;
    unset($filteredData[1]);
    writeType(FOURTH_OUTPUT, $directory, $fileFormat, $filteredData);

    $infoObject = new InfoHandler();
    $data = $infoObject->getInfoAboutFiles($directory, $filePointer);
    $infoObject->writeFile($directory, 'infoAboutFiles.txt', $data);

    echo 'Ready!' . PHP_EOL;

    return true;
}
