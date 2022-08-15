5-php_Dima
==========
Handler for csv, xlsx file formats
----------------------------------
### OS and version
- linux mint 20.01
- PHP 7.3.3 version
### How to run script
There is 2 variant:
Go to the file dir and run

- `php index.php '5_input_data_2.csv'`
- `php index.php '5_input_data_2.csv' 'xlsx''`

first variant has default output dir "output" and file format "csv".

### What the task?

- Add to .gitignore .idea, output and etc
- Make dir App and mv all classes to this dir 
- check code with codesniffer and resolve problems
- read some materials about composer
- add lib [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) and continue develop (add xlsx export)
- Write normal README

### Classes
#### FileHandler
Base class 

methods:
- `public function parse(array $data): array`
- `public function reParse(array $data): array`
- `private function convertField(string $field): string`

   convert key or field from header for call the method
- `public function readFile(string $file)`
- `public function writeFile(string $directory, string $file, $data): bool`
- `protected function makeDirectory($directoryName): bool`
- `protected function isFileExists(string $file): bool`


#### CsvHandler
extends [FileHandler](#FileHandler)

methods:

- `public function parseRow($element): array`

  Method for parent class which do another parse
- `public function writeFile(string $directory, string $file, $data): bool`


#### XlsxHandler
extends [FileHandler](#FileHandler)

methods:

- `public function readFile(string $file): array`
- `public function writeFile(string $directory, string $file, $data): bool`


#### InfoHandler
extends [FileHandler](#FileHandler)
Make info about all files 

methods:

- `public function getInfoAboutFiles(string $directory, string $fileFormat, array $counters): string`

  return info about all output files 

#### TxtHandler
Handle Txt Files 

- `public function superAnotherParse($data): array`

#### DataFilter
Filter data by arguments 

methods:

- `public function filterDataByCountrySplit(array $data, int $splitToWords): array`
- `public function filterDataByCountry(array $data, string $country): array`
- `public function filterDataByLatOrLng(array $data, int $number): array`
- `public function getAllDataPopForm(array $data): array`
- `public function filterDataByCity(array $data, string $city): array`
- `public function filterDataSameLetter(array $data): array`
- `public function getRegionTowns(array $data, array $region): array`
- `public function getAllRegions(array $data, array $regions): array`
- `private function sortByPopulationDesc($a, $b): int`

   return sorted array for filter by country
- `private function getFormattedPopulation(int $population, int $byNumber, string $separator): string`

   return formatted population for getAllByPopulationDesc


#### FileHandlerFactory
Return object([CsvHandler](#CsvHandler) || [XlsxHandler](#XlsxHandler))

methods:

- `public function create($type)`

#### FileHandlerFacade

- ` public static function getInstance(): self` 

  Singleton Facade
- ` public function runFileHandler(
  string $filePointer,
  string $directory = 'output',
  string $fileFormat = 'csv'): void`

#### FileFormatAdapterInterface
Interface for adapters 

- `public function parse(array $data)`

#### InfoAdapter
Impliments [FileformatAdapterInterface](#FileFormatAdapterInterface)

- ` public function parse(array $data): array`


#### TxtAdapter
Impliments [FileformatAdapterInterface](#FileFormatAdapterInterface)

- ` public function parse(array $data): array`

#### FileHandlerException
extends Exception

Exception class for files error 

#### DataException
extends Exception

Exception class for data handle error 

#### FlexibleDataObject
Class with magic call function for unknown fields of header
which can set or get field into spreadsheet or csv files 

- `public function __call($name, $arguments)`

  Special call function which can be get or set for unknown fields

- `public function addField($newField)`

  Add new field into header and table

- `public function deleteField($field)`
- `public function getkeys(): array`

   Return keys of data array in object
- `public function __tostring()`
 
  Magic function for giving rules when we use this class

#### City 
extends [FlexibleDataObject](#FlexibleDataObject)
Class with strictly methods for city format  
- `public function getCityAscii(): string`
- `public function getLat(): string`
- `public function getLng(): string`
- `public function getIso2(): string`
- `public function getIso3(): string`
- `public function getCountry(): string`
- `public function getPopulation(): string`


- `public function setCityAscii(string $value): void`
- `public function setLat(string $value): void`
- `public function setLng(string $value): void`
- `public function setIso2(string $value): void`
- `public function setIso3(string $value): void`
- `public function setCountry(string $value): void`
- `public function setPopulation(string $value): void`