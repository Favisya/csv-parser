<?php

require_once 'App/FileHandler.php';
require_once 'App/CsvHandler.php';
require_once 'App/DataFilter.php';
require_once 'App/InfoHandler.php';
require_once 'App/XlsxHandler.php';
require_once 'App/Factory.php';

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
    $file = $outFileName . '.' . $fileFormat;

    $factory = new Factory();
    $factory
        ->create($fileFormat)
        ->writeFile($directory, $file, $data);

    return true;
}

function readType(string $file): array
{
    $explodeFileName = explode('.', $file);

    $factory = new Factory();
    $object = $factory->create($explodeFileName[1]);

    return $object->parse($object->readFile($file));
}

function handler(
    string $filePointer,
    string $directory  = 'output',
    string $fileFormat = 'csv'
): bool {
    if ($fileFormat !== 'csv' && $fileFormat !== 'xlsx') {
        echo'Incorrect file format!' . PHP_EOL;
        return false;
    }

    $parsedData = readType($filePointer);
    if ((bool) $parsedData == false) {
       return false;
    }

    $filterObject = new DataFilter();

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
