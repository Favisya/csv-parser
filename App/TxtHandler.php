<?php

class TxtHAndler
{
    public function superAnotherParse($data): array
    {
        if (empty($data)) {
            throw new DataException('Input data is empty');
        }

        $parsedData = [];
        foreach ($data as $element) {
            $res = explode('|', $element);
            $parsedData[] = [
                'city' => $res[0],
                'lat' => $res[1],
                'lng' => $res[2],
                'country' => $res[3],
                'iso' => $res[4],
                'population' => $res[5]
            ];
        }

        return $parsedData;
    }
}
