<?php

class Factory
{
    public function create($type)
    {
        switch ($type) {
            case 'xlsx':
                return new XlsxHandler();
            case 'csv':
            default:
                return new CsvHandler();
        }
    }
}
