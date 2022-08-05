<?php

class Factory
{
    public function create($type)
    {
        switch ($type) {
            case FORMATS['xlsx']:
                return new XlsxHandler();
            case FORMATS['csv']:
                return new CsvHandler();
            default:
                return '';
        }
    }
}
