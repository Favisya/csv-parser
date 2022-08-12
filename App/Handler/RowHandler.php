<?php

namespace App\Handler;

use App\Exception\DataException;

class RowHandler
{
    private $data = [];

    public function __construct(array $header)
    {
        foreach ($header as $field) {
            $this->data[$field] = $field;
        }
    }

    public function __call($name, $arguments)
    {
        $field = substr($name, 3);

        (bool) $isGet = stripos($name, 'get') !== false;
        (bool) $isSet = stripos($name, 'set') !== false;

        if ($isSet) {
            foreach ($this->data as $item) {
                (bool) $isFieldHeader = stripos($item, $field) !== false;
                if ($isFieldHeader) {
                    $this->data[$item] = $arguments[0];
                    return true;
                }
            }
        } elseif ($isGet) {
            $dataKeys = array_keys($this->data);
            foreach ($dataKeys as $item) {
                (bool) $isFieldHeader = stripos($item, $field) !== false;
                if ($isFieldHeader) {
                    return $this->data[$item];
                }
            }
        }
        throw new DataException('incorrect method name');
    }

    public function addField($newField)
    {
        $this->data += $newField;
    }

    public function deleteField($field)
    {
        unset($this->data[$field]);
    }

    public function getkeys(): array
    {
        return array_keys($this->data);
    }

    public function __tostring()
    {
        return implode(', ', $this->data) . PHP_EOL;
    }
}
