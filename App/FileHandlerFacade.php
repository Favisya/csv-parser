<?php

class FileHandlerFacade
{
    protected $fileHandlerFactory;
    protected $adapter;
    protected $dataFilter;
    protected $infoObject;

    public function __construct()
    {
        $this->infoObject         = new InfoHandler();
        $this->dataFilter         = new DataFilter();
        $this->fileHandlerFactory = new FileHandlerFactory();
    }

    public function runFileHandler(
        string $filePointer,
        string $directory   = 'output',
        string $fileFormat  = 'csv'
    ): void {

        $explodeFileName = explode('.', $filePointer);

        if (!$this->fileHandlerFactory->create($explodeFileName[1])) { //ask about it Vlad
            echo 'Incorrect file format!' . PHP_EOL;                   //change
            //return false;
        }

        $counters = [];

        $object = $this->fileHandlerFactory->create($explodeFileName[1]);
        $parsedData = $object->parse($object->readFile($filePointer), $explodeFileName[1]);

        if ($parsedData == false) {
           // return false;
        }

        $this->fileHandlerFactoryObject = $this->fileHandlerFactory->create($fileFormat);

        $filteredData = $this
            ->dataFilter
            ->filterDataByCountrySplit($parsedData, 1);
        $counters[] = count($filteredData) - 1;

        $this
            ->fileHandlerFactoryObject
            ->writeFile($directory, FIRST_OUTPUT . '.' . $fileFormat, $filteredData);

        $filteredData = $this
            ->dataFilter
            ->filterDataByCountry($parsedData, 'Russia');
        $counters[] = count($filteredData) - 1;

        $this
            ->fileHandlerFactoryObject
            ->writeFile($directory, SECOND_OUTPUT . '.' . $fileFormat, $filteredData);

        $filteredData = $this
            ->dataFilter
            ->filterDataByLatOrLng($parsedData, 0);
        $counters[] = count($filteredData) - 1;

        $this
            ->fileHandlerFactoryObject
            ->writeFile($directory, THIRD_OUTPUT . '.' . $fileFormat, $filteredData);

        $filteredData = $this
            ->dataFilter
            ->getAllDataPopForm($parsedData);
        unset($filteredData[1]);
        $counters[] = count($filteredData) - 1;

        $this
            ->fileHandlerFactoryObject
            ->writeFile($directory, FOURTH_OUTPUT . '.' . $fileFormat, $filteredData);
        $counters[] = count($parsedData) - 1;

        $this->adapter = new Adapter(new InfoHandler(), $directory, $fileFormat);

        $data = $this
            ->adapter
            ->parse($counters);

        $this
            ->adapter
            ->writeFile($directory, 'infoAboutFiles.txt', $data, FILE_APPEND);

        echo 'Ready!' . PHP_EOL;
    }
}
