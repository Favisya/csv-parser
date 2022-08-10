<?php

namespace App\FileFormatAdapter;

interface FileFormatAdapterInterface
{
    public function parse(array $data);
}
