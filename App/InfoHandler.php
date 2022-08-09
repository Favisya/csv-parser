<?php

class InfoHandler
{
    public function getInfoAboutFiles(string $directory, string $fileFormat, array $counters): array
    {
        $inputCount = end($counters);
        $files = [];
        foreach (glob($directory . '/' . "*.$fileFormat") as $i => $filename) {
            $filename = explode('/', $filename);
            $files[] = [
                'file'  => $filename[1],
                'count' => $counters[$i]
            ];
        }

        $parsedText = [];
        $parsedText[] = 'input rows: ' . $inputCount . PHP_EOL;
        $parsedText[] = 'OUTPUT' . PHP_EOL;
        foreach ($files as $file) {
            $parsedText[] = $file['file'] . ' rows: ' . $file['count'] . PHP_EOL;
        }

        return $parsedText;
    }
}
