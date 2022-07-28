<?php

CONST CVS_HEADER = "\"city_ascii\",\"lat\",\"lng\",\"country\",\"iso2\",\"iso3\",\"population\"\n";

function parseCsv (string $inputFilePointer)
{
    if (file_exists ($inputFilePointer) ) {
        echo "File $inputFilePointer exists\n";

        if (is_dir ('output') ) {
            echo "output exist\n";
        } else {
            echo "creating output directory\n";
            mkdir ('output', 0777);
        }


        $parseArray = file ($inputFilePointer,FILE_IGNORE_NEW_LINES);

        $parsedArray =array ();
        foreach ($parseArray as $string) {
            $res = explode (",", $string);

            $tempArray = array (
                "city" => $res[0],
                "lat" =>  (float) $res[1],
                "lng" =>  (float) $res[2],
                "country" => $res[3],
                "iso2" => $res[4],
                "iso3" => $res[5],
                "population" => (integer) $res[6]
            );
            array_push ($parsedArray, $tempArray);
        }

        if (file_exists ("output/output_data_1.csv") ) {
            unlink ("output/output_data_1.csv");
            file_put_contents ("output/output_data_1.csv",CVS_HEADER);
        }

        foreach ($parsedArray as $i =>$element) {
            $temp = explode (" ", $element["country"]);
            if (count ($temp) > 1) {
                $inputText = $element["city"].",".$element["lat"].",".$element["lng"].",".$element["country"].",".
                    $element["iso2"].",".$element["iso3"].",".$element["population"]."\n";

                file_put_contents ('output/output_data_1.csv', $inputText, FILE_APPEND);
            }
        }

    } else {
        echo "File $inputFilePointer does not exists\n";
    }
}

$inputData = '5_input_data_2.csv';

parseCsv($inputData);
