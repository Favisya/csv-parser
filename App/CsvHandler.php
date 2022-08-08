<?php

class CsvHandler extends FileHandler
{
    public function parseRow($element): array
    {
        return str_getcsv($element);
    }

    public function writeFile(string $directory, string $file, array $data): bool
    {
        $path = $directory . '/' . $file;
        $this->makeDirectory($directory);

        if (empty($data)) {
            throw new DataExceptions('Output data is empty');
        }

        $fileOpen = fopen($path, 'w');
        foreach ($data as $element) {
            fputcsv($fileOpen, $element);
        }
        fclose($fileOpen);

        return true;
    }
}
