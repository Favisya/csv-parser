<?php

class InfoHandler extends FileHandler
{
    public function getInfoAboutFiles(string $directory, string $filePointer): string
    {
        $files =[];
        foreach (glob($directory . '/' . "*.*") as $filename) {
            $filename = explode('/', $filename);
            $files[] = $filename[1];
        }

        $outputText = 'input rows: '. $this->getRowsQty($filePointer) . PHP_EOL;
        $outputText .= 'OUTPUT' . PHP_EOL ;
        foreach ($files as $filename) {
            $outputText .= $filename . ' rows: ' . (count(file($directory . '/' . $filename)) - 1) . PHP_EOL;
        }

        return $outputText;
    }

    private function getRowsQty($file): int
    {
        return (count(file($file)) - 1);
    }
}