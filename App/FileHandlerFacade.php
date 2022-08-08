<?php

class FileHandlerFacade
{
    private static $instance;

    public static function getInstance()
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

        try {
            $this->handlerObject = $this->fileHandlerFactory->create($explodeFileName[1]);
        } catch (FileHandlerExceptions $e) {
            echo 'Error:' . $e->getMessage() . PHP_EOL;
        }

        try {
            $parsedData = $this
                ->handlerObject
                ->parse($this
                    ->handlerObject
                    ->readFile($filePointer));
        } catch (FileHandlerExceptions $e) {
            echo 'Error:' . $e->getMessage() . PHP_EOL;
        }

        try {
            $this->handlerObject = $this->fileHandlerFactory->create($fileFormat);
            $counters = [];
        } catch (FileHandlerExceptions $e) {
            echo 'Error:' . $e->getMessage() . PHP_EOL;
        }

        try {
            $filteredData = $this
                ->dataFilter
                ->filterDataByCountrySplit($parsedData, 1);
            $counters[] = count($filteredData) - 1;

            $this
                ->handlerObject
                ->writeFile($directory, FIRST_OUTPUT . '.' . $fileFormat, $filteredData);
        } catch (DataExceptions $e) {
            echo 'Filter error: ' . $e->getMessage() . PHP_EOL;
        }

        try {
            $filteredData = $this
                ->dataFilter
                ->filterDataByCountrySplit($parsedData, 1);
            $counters[] = count($filteredData) - 1;

            $this
                ->handlerObject
                ->writeFile($directory, FIRST_OUTPUT . '.' . $fileFormat, $filteredData);
        } catch (DataExceptions $e) {
            echo 'Filter error: ' . $e->getMessage() . PHP_EOL;
        }

        try {
            $filteredData = $this
                ->dataFilter
                ->filterDataByCountry($parsedData, 'Russia');
            $counters[] = count($filteredData) - 1;

            $this
                ->handlerObject
                ->writeFile($directory, SECOND_OUTPUT . '.' . $fileFormat, $filteredData);
        } catch (DataExceptions $e) {
            echo 'Filter error: ' . $e->getMessage() . PHP_EOL;
        }

        try {
            $filteredData = $this
                ->dataFilter
                ->filterDataByLatOrLng($parsedData, 0);
            $counters[] = count($filteredData) - 1;

            $this
                ->handlerObject
                ->writeFile($directory, THIRD_OUTPUT . '.' . $fileFormat, $filteredData);
        } catch (DataExceptions $e) {
            echo 'Filter error: ' . $e->getMessage() . PHP_EOL;
        }

        try {
            $filteredData = $this
                ->dataFilter
                ->getAllDataPopForm($parsedData);
            unset($filteredData[1]);
            $counters[] = count($filteredData) - 1;

            $this
                ->handlerObject
                ->writeFile($directory, FOURTH_OUTPUT . '.' . $fileFormat, $filteredData);
        } catch (DataExceptions $e) {
            echo $e->getMessage();
        }
        $counters[] = count($parsedData) - 1;

        $this->infoAdapter = new InfoAdapter(new InfoHandler(), $directory, $fileFormat);

        $data = $this
            ->infoAdapter
            ->parse($counters);

        $this->fileHandler->writeFile($directory, 'infoAboutFiles.txt', $data);
    }
}
