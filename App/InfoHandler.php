<?php

class InfoHandler extends FileHandler
{
    public function getInfoAboutFiles(string $directory, string $fileFormat, array $counters): string
    {
        $inputCount = $counters[count($counters) - 1];
        $files = [];
        foreach (glob($directory . '/' . "*.$fileFormat") as $i => $filename) {
            $filename = explode('/', $filename);
            $files[] = [
                'file'  => $filename[1],
                'count' => $counters[$i]
            ];
        }

        $outputText = 'input rows: ' . $inputCount . PHP_EOL;
        $outputText .= 'OUTPUT' . PHP_EOL ;
        foreach ($files as $file) {
            $outputText .= $file['file'] . ' rows: ' . $file['count'] . PHP_EOL;
        }

        return $outputText;
    }
}
