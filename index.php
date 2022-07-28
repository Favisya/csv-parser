<?php

CONST CVS_HEADER = "\"city_ascii\",\"lat\",\"lng\",\"country\",\"iso2\",\"iso3\",\"population\"\n";
CONST CVS_HEADER_FOURTH = "\"city_ascii\",\"lat\",\"lng\",\"country\",\"iso2\",\"iso3\",\"population\",\"population_formatted\"\n";
CONST FIRST_OUTPUT = "output_data_1.csv";
CONST SECOND_OUTPUT = "output_data_2.csv";
CONST THIRD_OUTPUT = "output_data_3.csv";
CONST FOURTH_OUTPUT = "output_data_4.csv";

function cmp($a, $b)
{
    if ($a["population"] == $b["population"]) {
        return 0;
    }
    return ($a["population"] < $b["population"]) ? -1 : 1;
}

function parseCsv(string $inputFilePointer)
{
    $first_Counter = 0;
    $second_Counter = 0;
    $third_Counter = 0;
    $fourth_Counter = 0;
    $input_Counter = 0;

    if (file_exists($inputFilePointer) ) {
        echo "File $inputFilePointer exists\n";

        if (is_dir('output') ) {
            echo "output exist\n";
        } else {
            echo "creating output directory\n";
            mkdir('output', 0777);
        }

        $parseArray = file ($inputFilePointer,FILE_IGNORE_NEW_LINES);

        $parsedArray =array ();
        foreach ($parseArray as $i => $string) {
            if ($i != 0) {
                $res = explode (",", $string);
                if (count($res) > 7) {
                    $tempArray = array (
                        "city" => $res[0],
                        "lat" =>  (float) $res[1],
                        "lng" =>  (float) $res[2],
                        "country" => $res[3].",".$res[4],
                        "iso2" => $res[5],
                        "iso3" => $res[6],
                        "population" => (integer) $res[7]
                    );
                    array_push($parsedArray, $tempArray);
                    echo '';
                } else {
                    $tempArray = array (
                        "city" => $res[0],
                        "lat" =>  (float) $res[1],
                        "lng" =>  (float) $res[2],
                        "country" => $res[3],
                        "iso2" => $res[4],
                        "iso3" => $res[5],
                        "population" => (integer) $res[6]
                    );
                    array_push($parsedArray, $tempArray);
                    echo '';
                }
            }
        }
        $input_Counter = count($parsedArray) + 1;

        if (file_exists("output/".FIRST_OUTPUT)) {
            unlink("output/".FIRST_OUTPUT);
            file_put_contents("output/".FIRST_OUTPUT,CVS_HEADER);
        }
        if (file_exists("output/".SECOND_OUTPUT)) {
            unlink("output/".SECOND_OUTPUT);
            file_put_contents("output/".SECOND_OUTPUT,CVS_HEADER);
        }
        if (file_exists("output/".THIRD_OUTPUT)) {
            unlink("output/".THIRD_OUTPUT);
            file_put_contents ("output/".THIRD_OUTPUT,CVS_HEADER);
        }
        if (file_exists ("output/".FOURTH_OUTPUT)) {
            unlink("output/".FOURTH_OUTPUT);
            file_put_contents("output/".FOURTH_OUTPUT,CVS_HEADER_FOURTH);
        }

        // 1_filter for countries in 2 words
        foreach ($parsedArray as $i => $element) {
            $temp = explode(" ", $element["country"]);
            if (count($temp) > 1) {
                $inputText = $element["city"].",".$element["lat"].",".$element["lng"].",".$element["country"].",".
                    $element["iso2"].",".$element["iso3"].",".$element["population"]."\n";

                file_put_contents('output/'.FIRST_OUTPUT, $inputText, FILE_APPEND);
                $first_Counter++;
            }
        }

        $first_Counter++;

        $russianArray = array();
        // 2_filter for city in Russia and sort by population in ASC
        foreach ($parsedArray as $i => $element) {
            if ($element["country"] == "\"Russia\"") {
                array_push($russianArray, $element);
            }
        }

        usort($russianArray,'cmp');

        foreach ($russianArray as $i => $element) {
            $inputText = $element["city"].",".$element["lat"].",".$element["lng"].",".$element["country"].",".
                $element["iso2"].",".$element["iso3"].",".$element["population"]."\n";

            file_put_contents('output/'.SECOND_OUTPUT, $inputText, FILE_APPEND);
        }
        $second_Counter = count($russianArray) + 1;

        // 3_filter where lat or lng is minus
        foreach ($parsedArray as $i => $element) {
            if ($element["lat"] < 0 || $element["lng"] < 0) {
                $inputText = $element["city"].",".$element["lat"].",".$element["lng"].",".$element["country"].",".
                    $element["iso2"].",".$element["iso3"].",".$element["population"]."\n";

                file_put_contents('output/'.THIRD_OUTPUT, $inputText, FILE_APPEND);
                $third_Counter++;
            }
        }

        $third_Counter++;

        // 4_filter add new column population_formatted
        foreach ($parsedArray as $i => $element) {
            $populationFormatted = null;

            if ($element["population"] >= 1000000) {
                $remainder = ($element["population"] / 1000000) % 1000000;
                $populationFormatted = $remainder."млн ";
            }
            if ($element["population"] >= 1000) {
                $remainder = $element["population"] / 1000 % 1000;
                $populationFormatted = $populationFormatted.$remainder."тыс ";
            }
            if ($element["population"] >= 1) {
                $remainder = $element["population"] % 1000;
                if ($remainder != 0) {
                    $populationFormatted = $populationFormatted.$remainder;
                }
            }

            $inputText = $element["city"].",".$element["lat"].",".$element["lng"].",".$element["country"].",".
                $element["iso2"].",".$element["iso3"].",".$element["population"].",".$populationFormatted."\n";

            file_put_contents('output/'.FOURTH_OUTPUT, $inputText, FILE_APPEND);
        }

        $fourth_Counter = count($parsedArray) + 1;

        $outputText = "input rows: ".$input_Counter."\n";
        $outputText = $outputText."OUTPUT\n".FIRST_OUTPUT." rows: ".$first_Counter."\n";
        $outputText = $outputText.SECOND_OUTPUT." rows: ".$second_Counter."\n";
        $outputText = $outputText.THIRD_OUTPUT." rows: ".$third_Counter."\n";
        $outputText = $outputText.FOURTH_OUTPUT." rows: ".$fourth_Counter."\n";

        file_put_contents('output/output_info.txt',$outputText);
    } else {
        echo "File $inputFilePointer does not exists\n";
    }
}

$inputData = '5_input_data_2.csv';

parseCsv($inputData);
