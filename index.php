<?php

CONST FIRST_OUTPUT  = 'output_data_1.csv';
CONST SECOND_OUTPUT = 'output_data_2.csv';
CONST THIRD_OUTPUT  = 'output_data_3.csv';
CONST FOURTH_OUTPUT = 'output_data_4.csv';

function sortByPopulationDesc($a, $b)
{
    if ($a['population'] == $b['population']) {
        return 0;
    }

    return ($a['population'] < $b['population']) ? -1 : 1;
}

function parseCsv(string $inputFilePointer)
{
    $firstCounter  = 0;
    $secondCounter = 0;
    $thirdCounter  = 0;
    $fourthCounter = 0;
    $inputCounter  = 0;

    if (!file_exists($inputFilePointer) ) {
        echo "File $inputFilePointer does not exists".PHP_EOL;
        return false;
    }
    echo "File $inputFilePointer exists".PHP_EOL;

    if (is_dir('output')) {
        echo 'output exist'.PHP_EOL;
    } else {
        echo 'creating output directory'.PHP_EOL;
        mkdir('output', 0777);
    }

    $parseData = file ($inputFilePointer, FILE_IGNORE_NEW_LINES);
    $parsedData = [];

    foreach ($parseData as $i => $element) {
        $res = str_getcsv($element, ',');

        $tempData = [
            'city'       => $res[0],
            'lat'        => $res[1],
            'lng'        => $res[2],
            'country'    => $res[3],
            'iso2'       => $res[4],
            'iso3'       => $res[5],
            'population' => $res[6]
        ];

        array_push($parsedData, $tempData);
    }
    $firstLineCsv = $parsedData[0];
    $inputCounter = count($parsedData);

    $filesToWrite = [FIRST_OUTPUT, SECOND_OUTPUT, THIRD_OUTPUT];
    foreach ($filesToWrite as $file) {
        $fileOpen = fopen('output/'.$file, 'w');
        fputcsv($fileOpen, $firstLineCsv);
        fclose($fileOpen);
    }


    foreach ($parsedData as $i => $element) {
        $temp = explode(' ', $element['country']);
        if (count($temp) > 1) {
            $firstFile = fopen('output/'.FIRST_OUTPUT, 'a');
            fputcsv($firstFile, $element);
            fclose($firstFile);
            $firstCounter++;
        }
    }

    $countryData = [];
    // 2_filter for city in Russia and sort by population in ASC
    foreach ($parsedData as $i => $element) {
        if ($element['country'] === 'Russia') {
            array_push($countryData, $element);
        }
    }

    usort($countryData,'sortByPopulationDesc');

    foreach ($countryData as $i => $element) {
        $fileOpen = fopen('output/'.SECOND_OUTPUT, 'a');
        fputcsv($fileOpen, $element);
        fclose($fileOpen);
    }
    $secondCounter = count($countryData);

    // 3_filter where lat or lng is minus
    foreach ($parsedData as $i => $element) {
        if ($element['lat'] < 0 || $element['lng'] < 0) {
            $fileOpen = fopen('output/'.THIRD_OUTPUT, 'a');
            fputcsv($fileOpen, $element);
            fclose($fileOpen);
            $thirdCounter++;
        }
    }

    // 4_filter add new column population_formatted
    $populationFiled = ['populationFormatted' => 'population_formatted'];
    $firstLineCsv += $populationFiled;

    $fileOpen = fopen('output/'.FOURTH_OUTPUT, 'w');
    fputcsv($fileOpen, $firstLineCsv);
    fclose($fileOpen);

    array_shift($parsedData);
    foreach ($parsedData as $i => $element) {
        $populationFormatted = null;

        if ($element['population'] >= 1000000) {
            $remainder = ($element['population'] / 1000000) % 1000000;
            $populationFormatted = $remainder.'млн ';
        }
        if ($element['population'] >= 1000) {
            $remainder = $element['population'] / 1000 % 1000;
            $populationFormatted = $populationFormatted.$remainder.'тыс ';
        }
        if ($element['population'] >= 1) {
            $remainder = $element['population'] % 1000;
            if ($remainder != 0) {
                $populationFormatted = $populationFormatted.$remainder;
            }
        }

        $outputText = [
            $element['city'],
            $element['lat'],
            $element['lng'],
            $element['country'],
            $element['iso2'],
            $element['iso3'],
            $element['population'],
            $populationFormatted
        ];

        $fileOpen = fopen('output/'.FOURTH_OUTPUT, 'a');
        fputcsv($fileOpen, $outputText);
        fclose($fileOpen);
    }

    $fourthCounter = count($parsedData);

    $outputText = 'input rows: '.$inputCounter.PHP_EOL;
    $outputText = $outputText.'OUTPUT'.PHP_EOL.FIRST_OUTPUT.' rows: '.$firstCounter.PHP_EOL;
    $outputText = $outputText.SECOND_OUTPUT.' rows: '.$secondCounter.PHP_EOL;
    $outputText = $outputText.THIRD_OUTPUT.' rows: '.$thirdCounter.PHP_EOL;
    $outputText = $outputText.FOURTH_OUTPUT.' rows: '.$fourthCounter.PHP_EOL;

    file_put_contents('output/output_info.txt', $outputText);
    echo 'Ready!'.PHP_EOL;
}