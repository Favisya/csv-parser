<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

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

    /**
     * Write xlsx data to file
     *
     * @param string $directory
     * @param string $file
     * @param array  $data
     *
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function writeFile(string $directory, string $file,array $data): bool
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
