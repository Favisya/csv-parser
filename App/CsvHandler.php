<?php

class CsvHandler extends FileHandler
{
    public function parseRow($element): array
    {
        return str_getcsv($element);
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
