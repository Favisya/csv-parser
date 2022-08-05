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

- `php -r "require 'index.php'; runHandler('5_input_data_2.csv');"`
- `php -r "require 'index.php'; runHandler('5_input_data_2.csv', 'output', 'xlsx');"`

first variant has default output dir "output" and file format "csv".

### What the task?

- Add to .gitignore .idea, output and etc
- Make dir App and mv all classes to this dir 
- check code with codesniffer and resolve problems
- read some materials about composer
- add lib [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) adn continue develop (add xlsx export)
- Write normal README

### Classes
#### FileHandler
Base class 

methods:
- `public function parse(array $data): array`
- `public function readFile(string $file)`
- `public function writeFile(string $directory, string $file, $data): bool`

   Help to choice file format for parser
- `protected function makeDirectory($directoryName): bool`
- `protected function isFileExists(string $file): bool`


#### CsvHandler
extends [FileHandler](#FileHandler)

methods:

- `public function parse(array $data): array`
- `public function readFile(string $file): array`
- `public function writeFile(string $directory, string $file, $data): bool`


#### XlsxHandler
extends [FileHandler](#FileHandler)

methods:

- `public function writeFile(string $directory, string $file, $data): bool`


#### InfoHandler
extends [FileHandler](#FileHandler)
Make info about all files 

methods:

- `public function getInfoAboutFiles(string $directory, string $fileFormat, array $counters): string`

  return info about all output files 

#### DataFilter
Filter data by arguments 

methods:

- `public function filterDataByCountrySplit(array $data, int $splitToWords): array`
- `public function filterDataByCountry(array $data, string $country): array`
- `public function filterDataByLatOrLng(array $data, int $number): array`
- `public function getAllDataPopForm(array $data): array`
- `private function sortByPopulationDesc($a, $b): int`

   return sorted array for filter by country
- `private function getFormattedPopulation(int $population, int $byNumber, string $separator): string`

   return formatted population for getAllByPopulationDesc


#### Factory
Return object([CsvHandler](#CsvHandler) || [XlsxHandler](#XlsxHandler))

methods:

- `public function create($type)`
