<?php

namespace App\Handler;

use App\SubHandler\XlsxHandler;
use App\SubHandler\CsvHandler;
use App\Exception\FileHandlerException;

const FORMAT_EXCEL = 'xlsx';
const FORMAT_CSV   = 'csv';

class FileHandlerFactory
{
    public function create($type)
    {
        switch ($type) {
            case FORMAT_EXCEL:
                return new XlsxHandler();
            case FORMAT_CSV:
                return new CsvHandler();
            default:
                throw new FileHandlerException('Incorrect file format');
        }
    }
}
