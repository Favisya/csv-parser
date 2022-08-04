<?php

require_once 'vendor/autoload.php';
require_once 'App/FileHandler.php';
require_once 'App/CsvHandler.php';
require_once 'App/DataFilter.php';
require_once 'App/InfoHandler.php';
require_once 'App/XlsxHandler.php';
require_once 'App/Factory.php';
require_once 'constants.php';

function runHandler(
    string $filePointer,
    string $directory  = 'output',
    string $fileFormat = 'csv'
): bool {
    $explodeFileName = explode('.', $filePointer);
    $factory = new Factory();

    if (!$factory->create($explodeFileName[1])) {
        echo 'Incorrect file format!' . PHP_EOL;
        return false;
    }

    $counters = [];

    $object = $factory->create($explodeFileName[1]);
    $parsedData = $object->parse($object->readFile($filePointer), $explodeFileName[1]);

    if ($parsedData == false) {
        return false;
    }

    $filterObject = new DataFilter();
    $factoryObject = $factory->create($fileFormat);

    $filteredData = $filterObject->filterDataByCountrySplit($parsedData, 1);
    $counters[] = count($filteredData) - 1;
    $factoryObject->writeFile($directory, FIRST_OUTPUT . '.' . $fileFormat, $filteredData);

    $filteredData = $filterObject->filterDataByCountry($parsedData, 'Russia');
    $counters[] = count($filteredData) - 1;
    $factoryObject->writeFile($directory, SECOND_OUTPUT . '.' . $fileFormat, $filteredData);

    $filteredData = $filterObject->filterDataByLatOrLng($parsedData, 0);
    $counters[] = count($filteredData) - 1;
    $factoryObject->writeFile($directory, THIRD_OUTPUT . '.' . $fileFormat, $filteredData);

    $filteredData = $filterObject->getAllDataPopForm($parsedData);
    unset($filteredData[1]);
    $counters[] = count($filteredData) - 1;
    $factoryObject->writeFile($directory, FOURTH_OUTPUT . '.' . $fileFormat, $filteredData);

    $counters[] = count($parsedData) - 1;

    $infoObject = new InfoHandler();
    $data = $infoObject->getInfoAboutFiles($directory, $fileFormat, $counters);
    $infoObject->writeFile($directory, 'infoAboutFiles.txt', $data);

    echo 'Ready!' . PHP_EOL;
    return true;
}
