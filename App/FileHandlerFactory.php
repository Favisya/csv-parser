<?php

class FileHandlerFactory
{
    public function create($type)
    {
        switch ($type) {
            case FORMATS['xlsx']:
                return new XlsxHandler();
            case FORMATS['csv']:
                return new CsvHandler();
            default:
                throw new FileHandlerException('Incorrect file format');
        }
    }
}
