<?php

class FileHandler
{
    const KEYS = [
        'city',
        'lat',
        'lng',
        'country',
        'iso2',
        'iso3',
        'population'
    ];

    public function parse(array $data): array
    {
        $parsedData = [];
        foreach ($data as $element) {
            $parsedData[] = [
                self::KEYS[0] => $element[0],
                self::KEYS[1] => $element[1],
                self::KEYS[2] => $element[2],
                self::KEYS[3] => $element[3],
                self::KEYS[4] => $element[4],
                self::KEYS[5] => $element[5],
                self::KEYS[6] => $element[6]
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

    /**
     * Help to choice file format for parser
     *
     * @param $element
     * @param string $fileFormat
     *
     * @return array
     */
    private function parseType($element, string $fileFormat): array
    {
        switch ($fileFormat) {
            case FORMATS['xlsx']:
                return $element;
            case FORMATS['csv']:
                return str_getcsv($element);
            default:
                return [];
        }
    }


    protected function makeDirectory($directoryName): bool
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
