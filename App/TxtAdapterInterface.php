<?php

class TxtAdapterInterface implements FileFormatAdapterInterface
{
    private $txtHandler;
    private $fileFormat;

    public function __construct(TxtHandler $txtHandler, string $fileFormat)
    {
        $this->txtHandler  = $txtHandler;
        $this->fileFormat  = $fileFormat;
    }

    public function parse(array $data): array
    {
        $convertData = $this->txtHandler->superAnotherParse($data);
        return $convertData;
    }
}