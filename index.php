<?php

function parseCsv(string $inputFilePointer)
{
    if(file_exists($inputFilePointer))
    {
        echo "File exists\n";

        if(is_dir('output')){
            echo "output exist\n";
        }else {
            echo "creating output directory\n";
            mkdir('output',0777);
        }

    }else {
        echo "File does not exists\n";
    }
}

$inputData = '5_input_data_2.csv';

parseCsv($inputData);
