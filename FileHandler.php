<?php

class FileHandler
{

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
        } else {
            mkdir($directoryName);
            return true;
        }
    }

    public function readFile(string $file): array
    {
        $data = [];
        if ($this->isFileExists($file)) {
            $data = file ($file, FILE_IGNORE_NEW_LINES);
            return $data;
        }

        return $data;
    }

    public function  writeFile(string $directory, string $file, $data): bool
    {
        $path = $directory . '/' . $file;
        if ($this->makeDirectory($directory)) {
            return false;
        }

        file_put_contents($path, $data);

        return true;
    }

}