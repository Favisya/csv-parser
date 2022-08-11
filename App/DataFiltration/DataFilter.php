<?php

namespace App\DataFiltration;

use App\Exception\DataException;

class DataFilter
{
    public function filterDataByCountrySplit(array $data, int $splitToWords): array
    {
        if ($splitToWords < 1) {
            throw new DataException('Cant split to 1 and smaller');
        }

        $resultData[] = $data[0];
        foreach ($data as $element) {
            $temp = explode(' ', $element['country']);
            if (count($temp) > $splitToWords) {
                $resultData[] = $element;
            }
        }

        return $resultData;
    }

    public function filterDataByCountry(array $data, string $country): array
    {
        if (empty($country)) {
            throw new DataException('Parameter is empty');
        }

        $resultData = [];
        foreach ($data as $element) {
            if ($element['country'] === $country) {
                $resultData[] = $element;
            }
        }
        usort($resultData, [$this, 'sortByPopulationDesc']);

        array_unshift($resultData, $data[0]);
        return $resultData;
    }

    public function filterDataByCity(array $data, string $city): array
    {
        if (empty($city)) {
            throw new DataException('Parameter is empty');
        }

        $resultData = [];
        foreach ($data as $element) {
            $isSubStr = stripos($element['city'], $city) !== false;
            if ($isSubStr) {
                $resultData[] = $element;
            }
        }

        array_unshift($resultData, $data[0]);
        return $resultData;
    }

    public function filterDataSameLetter(array $data): array
    {
        if (empty($data)) {
            throw new DataException('input data is empty');
        }

        $resultData = [];
        foreach ($data as $element) {
            if ($element['city'][0] === $element['country'][0]) {
                $resultData[] = $element;
            }
        }

        return $resultData;
    }

    public function filterDataByLatOrLng(array $data, int $number): array
    {
        $resultData[] = $data[0];
        foreach ($data as $element) {
            if ($element['lat'] < $number || $element['lng'] < $number) {
                $resultData[] = $element;
            }
        }

        return $resultData;
    }

    public function getAllDataPopForm(array $data): array
    {
        $populationField  = ['populationFormatted' => 'population_formatted'];
        $data[0] += $populationField;
        $resultData[] = $data[0];

        foreach ($data as $element) {
            $populationFormatted  = $this->getFormattedPopulation((int)$element['population'], 1000000, 'млн');
            $populationFormatted .= $this->getFormattedPopulation((int)$element['population'], 1000, 'тыс');
            $populationFormatted .= $this->getFormattedPopulation((int)$element['population'], 1, '');
            $element[]    = $populationFormatted;
            $resultData[] = $element;
        }

        unset($resultData[1]);
        return $resultData;
    }

    public function getRegionTowns(array $data, array $region): array
    {
        if (empty($data)) {
            throw new DataException('input data is empty!');
        }
        $minLng = min($region['east'], $region['west']);
        $maxLng = max($region['east'], $region['west']);
        $minLat = min($region['north'], $region['south']);
        $maxLat = max($region['north'], $region['south']);

        $resultData = [];
        foreach ($data as $element) {
            if (
                $element['lng'] >= $minLng && $element['lng'] <= $maxLng
                && $element['lat'] >= $minLat && $element['lat'] <= $maxLat
            ) {
                $resultData[] = $element;
            }
        }

        return $resultData;
    }

    public function getAllRegions(array $data, array $regions): array
    {
        $resultData = [];
        foreach ($regions as $region) {
            $resultData[] = $this->getRegionTowns($data, $region);
        }

        return $resultData;
    }

    private function sortByPopulationDesc($a, $b): int
    {
        if ($a['population'] == $b['population']) {
            return 0;
        }

        return ($a['population'] < $b['population']) ? -1 : 1;
    }

    private function getFormattedPopulation(int $population, int $byNumber, string $separator): string
    {
        $populationFormatted = '';
        if ($population >= $byNumber) {
            if ($byNumber === 1) {
                $remainder = ($population / $byNumber) % ($byNumber * 1000);
            } else {
                $remainder = ($population / $byNumber) % $byNumber;
            }

            if ($remainder !== 0) {
                $populationFormatted = $remainder . "$separator ";
            }
        }
        return $populationFormatted;
    }
}
