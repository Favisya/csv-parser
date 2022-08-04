<?php

class InfoHandler extends FileHandler
{
    public function getInfoAboutFiles(string $directory, string $fileFormat, array $counters): string
    {
        $files = [];
        foreach (glob($directory . '/' . "*.$fileFormat") as $filename) {
            $filename = explode('/', $filename);
            $files[] = $filename[1];
        }

        $outputText = 'input rows: ' . ($counters[4] - 1) . PHP_EOL;
        $outputText .= 'OUTPUT' . PHP_EOL ;
        for ($i = 0; $i < count($files); $i++) {
            $outputText .= $files[$i] . ' rows: ' . ($counters[$i] - 1) . PHP_EOL;
        }

        return $outputText;
    }
}
