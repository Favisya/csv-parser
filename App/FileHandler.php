<?php

class FileHandler
{
    public function readFile(string $file): array
    {
        if ($this->isFileExists($file)) {
            return file($file, FILE_IGNORE_NEW_LINES);
        }

        return $data = [];
    }

    public function writeFile(string $directory, string $file, $data): bool
    {
        $path = $directory . '/' . $file;
        $this->makeDirectory($directory);

        return (bool) file_put_contents($path, $data);
    }


    protected function isFileExists(string $file): bool
    {
        if (!file_exists($file)) {
            echo "File $file does not exists" . PHP_EOL;
            return false;
        }
        echo "File $file exists" . PHP_EOL;

        return true;
    }

    protected function makeDirectory($directoryName): bool
    {
        if (is_dir($directoryName)) {
            return false;
        }

        mkdir($directoryName);
        return true;
    }
}
