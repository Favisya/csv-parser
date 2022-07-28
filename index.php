<?php

function parseCsv (string $inputFilePointer)
{
    if (file_exists ($inputFilePointer) ) {
        echo "File exists\n";

        if (is_dir ('output') ) {
            echo "output exist\n";
        } else {
            echo "creating output directory\n";
            mkdir ('output', 0777);
        }


        $parseArray = file ($inputFilePointer,FILE_IGNORE_NEW_LINES);

        $parsedArray =array ();
        foreach ($parseArray as $string) {
            $res = explode(",", $string);

            foreach ($res as $i => $str) {
                $res[$i] = trim($str, '"');
            }

            $tempArray = array (
                "city" => $res[0],
                "lat" => (float) $res[1],
                "lng" => (float) $res[2],
                "country" => $res[3],
                "iso2" => $res[4],
                "iso3" => $res[5],
                "pop" => (integer) $res[6]
            );
            array_push ($parsedArray, $tempArray);
        }

    } else {
        echo "File does not exists\n";
    }
}

//$inputData = '5_input_data_2.csv';

//parseCsv($inputData);
