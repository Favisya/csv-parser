<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as xlsxRead;

class XlsxHandler extends FileHandler
{
    public function readFile(string $file): array
    {
        if (!$this->isFileExists($file)) {
            return $data = [];
        }

        $reader = new xlsxRead();
        $spreadSheet = $reader->load($file);

        $data = [];
        foreach ($spreadSheet->getSheet(0)->toArray() as $i => $element) {
            $data[] = [
                'city'       => $element[0],
                'lat'        => $element[1],
                'lng'        => $element[2],
                'country'    => $element[3],
                'iso2'       => $element[4],
                'iso3'       => $element[5],
                'population' => $element[6]
            ];
        }

        return $data;
    }

    public function writeFile(string $directory, string $file, $data): bool
    {
        $this->makeDirectory($directory);

        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $sheet->fromArray($data);

        $writer = new Xlsx($spreadSheet);
        $writer->save($directory . '/' . $file);

        return true;
    }
}
