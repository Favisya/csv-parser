<?php

class Adapter extends FileHandler
{
    public $dataFilter;

    public function __construct(DataFilter $dataFilter)
    {
        $this->dataFilter = $dataFilter;
    }

}