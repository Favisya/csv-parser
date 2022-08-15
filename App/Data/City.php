<?php

namespace App\Data;

class City extends FlexibleDataObject
{
    public function getCityAscii(): string
    {
        return $this->data['city_ascii'];
    }

    public function getLat(): string
    {
        return $this->data['lat'];
    }

    public function getLng(): string
    {
        return $this->data['lng'];
    }

    public function getCountry(): string
    {
        return $this->data['country'];
    }

    public function getIso2(): string
    {
        return $this->data['iso2'];
    }

    public function getIso3(): string
    {
        return $this->data['iso3'];
    }

    public function getPopulation(): int
    {
        return (int) $this->data['population'];
    }

    public function setCityAscii(string $value): void
    {
        $this->data['city_ascii'] = $value;
    }

    public function setLat(string $value): void
    {
        $this->data['lat'] = $value;
    }

    public function setLng(string $value): void
    {
        $this->data['lng'] = $value;
    }

    public function setCountry(string $value): void
    {
        $this->data['country'] = $value;
    }

    public function setIso2(string $value): void
    {
        $this->data['iso2'] = $value;
    }

    public function setIso3(string $value): void
    {
        $this->data['iso3'] = $value;
    }

    public function setPopulation(string $value): void
    {
        $this->data['population'] = $value;
    }
}