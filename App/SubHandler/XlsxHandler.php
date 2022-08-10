<?php

namespace App\SubHandler;

use App\FileHandler\Handler;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XlsxHandler extends FileHandler
{
    public function readFile(string $file): array
    {
        if (!$this->isFileExists($file)) {
            return [];
        }

        $reader = new XlsxReader();
        $spreadSheet = $reader->load($file);

        return $spreadSheet->getSheet(0)->toArray();
    }

    public function writeFile(string $directory, string $file, array $data): bool
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
