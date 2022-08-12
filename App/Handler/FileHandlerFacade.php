<?php

namespace App\Handler;

use App\DataFiltration\DataFilter;
use App\Exception\FileHandlerException;
use App\Exception\DataException;
use App\SubHandler\InfoHandler;
use App\FileFormatAdapter\InfoAdapter;

class FileHandlerFacade
{
    private static $instance;

    public static function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new FileHandlerFacade();
        }

        return self::$instance;
    }

    protected $fileHandlerFactory;
    protected $fileHandler;
    protected $handlerObject;
    protected $infoAdapter;
    protected $dataFilter;

    public function __construct()
    {
        $this->fileHandler        = new FileHandler();
        $this->dataFilter         = new DataFilter();
        $this->fileHandlerFactory = new FileHandlerFactory();
    }

    public function runFileHandler(
        string $filePointer,
        string $directory = 'output',
        string $fileFormat = 'csv'
    ): void {
        $explodeFileName = explode('.', $filePointer);

        $infoCounters = [];

        try {
            $this->handlerObject = $this->fileHandlerFactory->create($explodeFileName[1]);

            $readFile = $this->handlerObject->readFile($filePointer);
            $parsedData = $this->handlerObject->parse($readFile);

            $this->handlerObject = $this->fileHandlerFactory->create($fileFormat);
        } catch (FileHandlerException $e) {
            echo 'Error:' . $e->getMessage() . PHP_EOL;
        }

        try {
            $filteredData = $this->dataFilter->filterDataByCountrySplit($parsedData, 1);
            $infoCounters[] = count($filteredData) - 1;
            $filteredData = $this->handlerObject->reParse($filteredData);
            $this->handlerObject->writeFile($directory, FIRST_OUTPUT . '.' . $fileFormat, $filteredData);

            $filteredData = $this->dataFilter->filterDataByCountry($parsedData, 'Russia');
            $infoCounters[] = count($filteredData) - 1;
            $filteredData = $this->handlerObject->reParse($filteredData);
            $this->handlerObject->writeFile($directory, SECOND_OUTPUT . '.' . $fileFormat, $filteredData);

            $filteredData = $this->dataFilter->filterDataByLatOrLng($parsedData, 0);
            $infoCounters[] = count($filteredData) - 1;
            $filteredData = $this->handlerObject->reParse($filteredData);
            $this->handlerObject->writeFile($directory, THIRD_OUTPUT . '.' . $fileFormat, $filteredData);

            $filteredData = $this->dataFilter->getAllDataPopForm($parsedData);
            $infoCounters[] = count($filteredData) - 1;
            $data = $filteredData;
            $filteredData = $this->handlerObject->reParse($filteredData);
            $this->handlerObject->writeFile($directory, FOURTH_OUTPUT . '.' . $fileFormat, $filteredData);
            foreach ($data as $item) {
                $item->deleteField('population_formatted');
            }

            $filteredData = $this->dataFilter->filterDataByCity($parsedData, 'saint');
            $infoCounters[] = count($filteredData) - 1;
            $filteredData = $this->handlerObject->reParse($filteredData);
            $this->handlerObject->writeFile($directory, FIFTH_OUTPUT . '.' . $fileFormat, $filteredData);

            $filteredData = $this->dataFilter->filterDataSameLetter($parsedData);
            $infoCounters[] = count($filteredData) - 1;
            $filteredData = $this->handlerObject->reParse($filteredData);
            $this->handlerObject->writeFile($directory, SIX_OUTPUT . '.' . $fileFormat, $filteredData);

            $filteredData = $this->dataFilter->getAllRegions($parsedData, EXTREME_POINTS);
            foreach ($filteredData as $i => $item) {
                $infoCounters[] = count($item) - 1;
                $filteredData[$i] = $this->handlerObject->reParse($item);
            }
            $i = 0;
            foreach (REGION_PREFIX as $region) {
                $this->handlerObject->writeFile(
                    $directory,
                    SEVEN_OUTPUT . "_$region." . $fileFormat,
                    $filteredData[$i++]
                );
            }

            $infoCounters[] = count($parsedData) - 1;
        } catch (DataException $e) {
            echo $e->getMessage();
        }

        $this->infoAdapter = new InfoAdapter(new InfoHandler(), $directory, $fileFormat);
        $data = $this->infoAdapter->parse($infoCounters);
        $this->fileHandler->writeFile($directory, 'infoAboutFiles.txt', $data);
    }
}
