<?php

namespace App\DataFiltration;

use App\Exception\DataException;
use App\Handler\RowHandler;

class DataFilter
{
    public function filterDataByCountrySplit(array $data, int $splitToWords): array
    {
        if ($splitToWords < 1) {
            throw new DataException('Cant split to 1 and smaller');
        }

        $resultData[] = $data[0];
        foreach ($data as $element) {
            $temp = explode(' ', $element->getCountry());
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
            if ($element->getCountry() === $country) {
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
            $isSubStr = stripos($element->getCity(), $city) !== false;
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
            if ($element->getCity()[0] === $element->getCountry()[0]) {
                $resultData[] = $element;
            }
        }

        return $resultData;
    }

    public function filterDataByLatOrLng(array $data, int $number): array
    {
        $resultData[] = $data[0];
        foreach ($data as $element) {
            if ($element->getLat() < $number || $element->getLng() < $number) {
                $resultData[] = $element;
            }
        }

        return $resultData;
    }

    public function getAllDataPopForm(array $data): array
    {
        $populationField  = ['population_formatted' => 'population_formatted'];
        $data[0]->{'addField'}($populationField);
        $resultData[] = $data[0];

        foreach ($data as $element) {
            $populationFormatted  = $this->getFormattedPopulation((int)$element->getPopulation(), 1000000, 'млн');
            $populationFormatted .= $this->getFormattedPopulation((int)$element->getPopulation(), 1000, 'тыс');
            $populationFormatted .= $this->getFormattedPopulation((int)$element->getPopulation(), 1, '');
            $populationFormatted = ['population_formatted' => $populationFormatted];

            $element->{'addField'}($populationFormatted);
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
                $element->getLng() >= $minLng && $element->getLng() <= $maxLng
                && $element->getLat() >= $minLat && $element->getLat() <= $maxLat
            ) {
                $resultData[] = $element;
            }
        }

        array_unshift($resultData, $data[0]);
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
        if ($a->getPopulation() == $b->getPopulation()) {
            return 0;
        }

        return ($a->getPopulation() < $b->getPopulation()) ? -1 : 1;
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
