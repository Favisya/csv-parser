<?php

namespace App\FileFormatAdapter;

use App\SubHandler\InfoHandler;

class InfoAdapter implements FileFormatAdapterInterface
{
    private $infoHandler;
    private $directory;
    private $fileFormat;

    public function __construct(InfoHandler $infoHandler, string $directory, string $fileFormat)
    {
        $this->infoHandler = $infoHandler;
        $this->directory   = $directory;
        $this->fileFormat  = $fileFormat;
    }

    public function parse(array $data): array
    {
        $convertData = $this
            ->infoHandler
            ->getInfoAboutFiles($this->directory, $this->fileFormat, $data);
        return $convertData;
    }
}
