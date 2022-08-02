<?php

class CsvFilter extends CsvHandler
{
    private function getFormattedPopulation(int $population): string
    {
        $populationFormatted = null;

        if ($population >= 1000000) {
            $remainder = ($population / 1000000) % 1000000;
            $populationFormatted = $remainder . 'млн ';
        }
        if ($population >= 1000) {
            $remainder = $population / 1000 % 1000;
            $populationFormatted = $populationFormatted . $remainder . 'тыс ';
        }
        if ($population >= 1) {
            $remainder = $population % 1000;
            if ($remainder != 0) {
                $populationFormatted = $populationFormatted . $remainder;
            }
        }
        return (string)$populationFormatted;
    }

    public function FilterDataByCountrySplit(array $data): array
    {
        $resultData = [];
        foreach ($data as $element) {
            $temp = explode(' ', $element['country']);
            if (count($temp) > 1) {
                $resultData[] = $element;
            }
        }

        return $resultData;
    }

    public function FilterDataByCountry(array $data, string $country): array
    {
        //govnokod.ru  expects parameter 2 to be a valid callback, function 'sortByPopulationDesc' not found or invalid
        // function name if I add this func in class method
        function sortByPopulationDesc($a, $b): int
        {
            if ($a['population'] == $b['population']) {
                return 0;
            }

            return ($a['population'] < $b['population']) ? -1 : 1;
        }

        $resultData = [];
        foreach ($data as $element) {
            if ($element['country'] === $country) {
                $resultData[] = $element;
            }
        }
        usort($resultData, 'sortByPopulationDesc');

        return $resultData;
    }

    public function FilterDataByLatOrLng(array $data, int $number): array
    {
        $resultData = [];
        foreach ($data as $element) {
            if ($element['lat'] < $number || $element['lng'] < $number) {
                $resultData[] = $element;
            }
        }

        return $resultData;
    }

    public function allDataPopulationFormatted(array $data): array
    {
        $resultData = [];

        foreach ($data as $element) {
            $populationFormatted = $this->getFormattedPopulation((int)$element['population']);
            $element[] = $populationFormatted;
            $resultData[] = $element;
        }

        return $resultData;
    }
}