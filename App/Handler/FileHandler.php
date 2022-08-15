<?php

namespace App\Handler;

use App\Data\City;
use App\Exception\DataException;
use App\Exception\FileHandlerException;

class FileHandler
{
    public function parse(array $data): array
    {
        if (empty($data)) {
            throw new FileHandlerException('Input data is empty');
        }

        $header = $this->parseRow($data[0]);

        $parsedData = [];
        foreach ($data as $element) {
            $element = $this->parseRow($element);
            $dataObject = new City($header);

            foreach ($header as $i => $field) {
                $field = $this->convertField($field);
                $dataObject->{"set$field"}($element[$i]);
            }

            $parsedData[] = $dataObject;
        }

        $dataObject = new City($header);
        echo $dataObject;

        return $parsedData;
    }

    public function reParse(array $data): array
    {
        $parsedData = [];
        if (empty($data)) {
            throw new DataException('Input data is empty');
        }

        $header = $data[0]->getKeys();
        foreach ($data as $element) {
            $tempData = [];
            foreach ($header as $field) {
                $field = $this->convertField($field);
                $tempData[] = $element->{"get$field"}();
            }

            $parsedData[] = $tempData;
        }
        return $parsedData;
    }

    public function readFile(string $file): array
    {
        if ($this->isFileExists($file)) {
            return file($file, FILE_IGNORE_NEW_LINES);
        }

        return [];
    }

    public function writeFile(string $directory, string $file, array $data): bool
    {
        $path = $directory . '/' . $file;
        $this->makeDirectory($directory);

        return file_put_contents($path, $data);
    }


    private function convertField(string $field): string
    {
        $convertField = explode('_', $field);
        $newField = '';

        foreach ($convertField as $item) {
            $newField .= ucfirst($item);
        }
        return $newField;
    }

    protected function parseRow($element): array
    {
        return $element;
    }

    protected function makeDirectory(string $directoryName): bool
    {
        if (is_dir($directoryName)) {
            return false;
        }

        mkdir($directoryName);
        return true;
    }

    protected function isFileExists(string $file): bool
    {
        if (!file_exists($file)) {
            throw new FileHandlerException("input file does not exists");
        }

        return true;
    }
}
