<?php

class CsvHandler extends FileHandler
{
    public function parse(array $data): array
    {
        $parsedData=[];
        foreach ($data as $i => $element) {
            $res = str_getcsv($element);
            $parsedData[] = [
                'city'       => $res[0],
                'lat'        => $res[1],
                'lng'        => $res[2],
                'country'    => $res[3],
                'iso2'       => $res[4],
                'iso3'       => $res[5],
                'population' => $res[6]
            ];
        }
        return $parsedData;
    }

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