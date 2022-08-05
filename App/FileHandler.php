<?php

class FileHandler
{
    const COLUMN_CITY       = 'city';
    const COLUMN_LAT        = 'lat';
    const COLUMN_LNG        = 'lng';
    const COLUMN_COUNTRY    = 'country';
    const COLUMN_ISO2       = 'iso2';
    const COLUMN_ISO3       = 'iso3';
    const COLUMN_POPULATION = 'population';

    public function parse(array $data): array
    {
        $parsedData = [];
        foreach ($data as $element) {
            $element = $this->parseRow($element);
            $parsedData[] = [
                self::COLUMN_CITY       => $element[0],
                self::COLUMN_LAT        => $element[1],
                self::COLUMN_LNG        => $element[2],
                self::COLUMN_COUNTRY    => $element[3],
                self::COLUMN_ISO2       => $element[4],
                self::COLUMN_ISO3       => $element[5],
                self::COLUMN_POPULATION => $element[6]
            ];
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

    /**
     * Write data to file
     *
     * @param string        $directory
     * @param string        $file
     * @param array|string  $data
     *
     * @return bool
     */
    public function writeFile(string $directory, string $file, $data): bool
    {
        $path = $directory . '/' . $file;
        $this->makeDirectory($directory);

        return (bool) file_put_contents($path, $data);
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
            echo "File $file does not exists" . PHP_EOL;
            return false;
        }
        echo "File $file exists" . PHP_EOL;

        return true;
    }
}
