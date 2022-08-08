<?php

class DataFilter
{
    public function filterDataByCountrySplit(array $data, int $splitToWords): array
    {
        if ($splitToWords < 1) {
            throw new DataExceptions('Cant split to 1 and smaller');
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
            throw new DataExceptions('Parameter is empty');
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
            $element[] = $populationFormatted;
            $resultData[] = $element;
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
