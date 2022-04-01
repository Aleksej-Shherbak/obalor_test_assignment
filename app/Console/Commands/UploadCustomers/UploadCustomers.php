<?php

namespace App\Console\Commands\UploadCustomers;

use App\Console\Dto\CsvLineParser\CsvLineDto;
use App\Console\Services\CsvLineParser;
use App\Console\Services\CsvLineProcessor;
use App\Console\Services\CsvReader;
use App\Infrastructure\Helpers\FilePathHelper;
use App\Infrastructure\Helpers\ReflectionHelper;
use App\Models\CountryCode;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Keboola\Csv\CsvWriter;
use Mavinoo\Batch\Batch;

class UploadCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers-uploader:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload customers from given csv file.';

    private Batch $batch;
    private CsvReader $csvReader;
    private CsvLineParser $csvLineParser;
    private CsvLineProcessor $csvLineProcessor;

    /**
     * @var resource
     */
    private $errorFile;
    private CsvWriter $csvWriter;

    private const SUCCESS_RESULT_CODE = 0;
    private const ERROR_RESULT_CODE = 1;
    private const ACCEPTABLE_FILE_EXT = 'csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CsvReader $csvReader, CsvLineParser $csvLineParser, CsvLineProcessor $csvLineProcessor, Batch $batch)
    {
        parent::__construct();

        $this->csvReader = $csvReader;
        $this->csvLineParser = $csvLineParser;
        $this->csvLineProcessor = $csvLineProcessor;
        $this->batch = $batch;
    }

    /**
     * Upload customers from csv file.
     *
     * @return int
     */
    public function handle(): int
    {
        $path = $this->askForFilePath();
        if (!$this->isValidPath($path)) {
            return self::ERROR_RESULT_CODE;
        }

        $positionMap = null;
        try {
            $headerLineRowArray = $this->csvReader->readHeaderLine($path);
            $positionMap = $this->csvLineParser->buildPositionsMapFromCsvHeaderLine($headerLineRowArray);
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            return self::ERROR_RESULT_CODE;
        }

        $countryCodes = CountryCode::all();

        $customersArraysToBulkInsert = [];
        foreach ($this->csvReader->readCsvFile($path) as $rowCsvLine) {
            $serviceResponse = $this->csvLineParser->parsLine($rowCsvLine, $positionMap);
            if ($serviceResponse->isFailed) {
                $this->writeErrorLineToCsv($serviceResponse->columnReasonOfFail, $positionMap, $rowCsvLine);
                continue;
            }
            if ($customerArray = $this->csvLineProcessor->processLine($serviceResponse->response, $countryCodes)) {

                $customersArraysToBulkInsert[] = $customerArray;
            }
        }
        $customerInstance = new Customer();
        $columns = ['email', 'birth_year', 'name', 'surname', 'location', 'country_code'];

        $result = true;
        if (!empty($customersArraysToBulkInsert)) {
            $result = $this->batch->insert($customerInstance, $columns, $customersArraysToBulkInsert);
        }

        if ($this->errorFile !== null) {
            fclose($this->errorFile);
        }

        if ($result !== false) {
            $this->info('Done! Insertion information: ' . json_encode($result, JSON_PRETTY_PRINT));
            return self::SUCCESS_RESULT_CODE;

        } else {
            $this->error('Insertion error.');
        }
        return self::SUCCESS_RESULT_CODE;
    }

    private function askForFilePath(): string
    {
        return $this->ask('Hello! please specify a path to .csv file with the following columns: ' .
            ReflectionHelper::getClassPublicPropertiesNamesList(CsvLineDto::class)->implode(','));
    }

    private function isValidPath(string $path): bool
    {
        if ($errorMessage = FilePathHelper::validatePath($path, self::ACCEPTABLE_FILE_EXT)) {
            $this->error($errorMessage);
            return false;
        }
        return true;
    }


    private function writeErrorLineToCsv(string $columTheReasonOfFail, Collection $arrayPositionMap, $row): void {

        if ($this->errorFile === null) {
            $this->errorFile = fopen( './test-output.csv', 'w+');

            $this->csvWriter = new CsvWriter($this->errorFile);
            $this->csvWriter->writeRow([
                'error', 'id', ...$arrayPositionMap->map(function($x) {return $x->csvColumnName; })->toArray()
            ]);
        }

        $this->csvWriter->writeRow([
            $columTheReasonOfFail, ...$row
        ]);

    }

}
