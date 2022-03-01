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
    private string $path;

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
        $this->path = $path;

        // I just can't break the habit when variables have block scope of visibility (not functional scope). I know that it's not gonna work in PHP, but ...
        $positionMap = null;
        try {
            $headerLineRowArray = $this->csvReader->readHeaderLine($this->path);
            $positionMap = $this->csvLineParser->buildPositionsMapFromCsvHeaderLine($headerLineRowArray);
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
            return self::ERROR_RESULT_CODE;
        }

        $countryCodes = CountryCode::all();

        $customersArraysToBulkInsert = [];
        foreach ($this->csvReader->readCsvFile($this->path) as $rowCsvLine) {
            $serviceResponse = $this->csvLineParser->parsLine($rowCsvLine, $positionMap);
            if ($serviceResponse->isFailed) {
                // todo write to excel why
                continue;
            }
            if ($customerArray = $this->csvLineProcessor->processLine($serviceResponse->response, $countryCodes)) {

                $customersArraysToBulkInsert[] = $customerArray;
            }
        }
        $customerInstance = new Customer();
        $columns = ['email', 'birth_year', 'name', 'surname', 'location', 'country_code'];
        $result = $this->batch->insert($customerInstance, $columns, $customersArraysToBulkInsert);

        if ($result !== false) {
            $this->info('Done! Insertion information: ' . print_r($result, true));
            return self::SUCCESS_RESULT_CODE;

        } else {
            $this->error('Insertion error.');
        }
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

}
