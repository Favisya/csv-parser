<?php

class CsvHandler extends FileHandler
{
    public function parse(array $data): array
    {
        $parsedData = [];
        foreach ($data as $element) {
            $element = str_getcsv($element);
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

    /**
     * Write csv data to file
     *
     * @param string $directory
     * @param string $file
     * @param array  $data
     *
     * @return bool
     */
    public function writeFile(string $directory, string $file, $data): bool
    {
        $path = $directory . '/' . $file;
        $this->makeDirectory($directory);

        $fileOpen = fopen($path, 'w');
        foreach ($data as $element) {
            fputcsv($fileOpen, $element);
        }
        fclose($fileOpen);

        return true;
    }
}
