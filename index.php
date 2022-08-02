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

function getFormattedPopulation(int $population): string
{
    $populationFormatted = null;

    if ($population >= 1000000) {
        $remainder = ($population / 1000000) % 1000000;
        $populationFormatted = $remainder . 'млн ';
    }
    if ($population >= 1000) {
        $remainder = $population / 1000 % 1000;
        $populationFormatted = $populationFormatted . $remainder . 'тыс ';
    }
    if ($population >= 1) {
        $remainder = $population % 1000;
        if ($remainder != 0) {
            $populationFormatted = $populationFormatted . $remainder;
        }
    }
    return (string) $populationFormatted;
}

function makeDirectory(): void
{
    if (is_dir('output')) {
        echo 'output exist' . PHP_EOL;
    } else {
        echo 'creating output directory' . PHP_EOL;
        mkdir('output');
    }
}

function parseCsv(string $inputFilePointer)
{
    $counters = [
    'first'  => 0,
    'second' => 0,
    'third'  => 0,
    'fourth' => 0,
    'input'  => 0
    ];
    if (!file_exists($inputFilePointer) ) {
        echo "File $inputFilePointer does not exists" . PHP_EOL;
        return false;
    }
    echo "File $inputFilePointer exists" . PHP_EOL;

    makeDirectory();

    $parseData = file ($inputFilePointer, FILE_IGNORE_NEW_LINES);
    $parsedData = [];

    foreach ($parseData as $i => $element) {
        $res = str_getcsv($element);
        $parsedData[] = [
            'city'       => $res[0],
            'lat'        => $res[1],
            'lng'        => $res[2],
            'country'    => $res[3],
            'iso2'       => $res[4],
            'iso3'       => $res[5],
            'population' => $res[6]
        ];
    }
    $firstLineCsv = array_shift($parsedData);
    $counters['input'] = count($parsedData);

    $filesToWrite = [FIRST_OUTPUT, SECOND_OUTPUT, THIRD_OUTPUT];
    foreach ($filesToWrite as $file) {
        $fileOpen = fopen('output/' . $file, 'w');
        fputcsv($fileOpen, $firstLineCsv);
        fclose($fileOpen);
    }

    $fileOpen = fopen('output/' . FIRST_OUTPUT, 'a');
    foreach ($parsedData as $element) {
        $temp = explode(' ', $element['country']);
        if (count($temp) > 1) {
            fputcsv($fileOpen, $element);
            $counters['first']++;
        }
    }
    fclose($fileOpen);

    $countryData = [];
    // 2_filter for city in Russia and sort by population in ASC
    foreach ($parsedData as $element) {
        if ($element['country'] === 'Russia') {
            $countryData[] = $element;
        }
    }

    usort($countryData,'sortByPopulationDesc');

    $fileOpen = fopen('output/' . SECOND_OUTPUT, 'a');
    foreach ($countryData as $element) {
        fputcsv($fileOpen, $element);
    }
    fclose($fileOpen);
    $counters['second'] = count($countryData);

    // 3_filter where lat or lng is minus

    $fileOpen = fopen('output/' . THIRD_OUTPUT, 'a');
    foreach ($parsedData as $element) {
        if ($element['lat'] < 0 || $element['lng'] < 0) {
            fputcsv($fileOpen, $element);
            $counters['third']++;
        }
    }
    fclose($fileOpen);

    // 4_filter add new column population_formatted
    $populationFiled = ['populationFormatted' => 'population_formatted'];
    $firstLineCsv += $populationFiled;

    $fileOpen = fopen('output/' . FOURTH_OUTPUT, 'w');
    fputcsv($fileOpen, $firstLineCsv);
    fclose($fileOpen);

    $fileOpen = fopen('output/' . FOURTH_OUTPUT, 'a');
    foreach ($parsedData as $element) {
        $populationFormatted = getFormattedPopulation((int) $element['population']);
        $element[] = $populationFormatted;
        fputcsv($fileOpen, $element);
    }
    fclose($fileOpen);
    $counters['fourth'] = count($parsedData);

    $outputInfo  = 'input rows: ' . $counters['input'] . PHP_EOL;
    $outputInfo .= 'OUTPUT' . PHP_EOL . FIRST_OUTPUT . ' rows: ' . $counters['first'] . PHP_EOL;
    $outputInfo .= SECOND_OUTPUT . ' rows: ' . $counters['second'] . PHP_EOL;
    $outputInfo .= THIRD_OUTPUT . ' rows: ' . $counters['third'] . PHP_EOL;
    $outputInfo .= FOURTH_OUTPUT . ' rows: ' . $counters['fourth'] . PHP_EOL;

    file_put_contents('output/output_info.txt', $outputInfo);
    echo 'Ready!' . PHP_EOL;

    return true;
}
