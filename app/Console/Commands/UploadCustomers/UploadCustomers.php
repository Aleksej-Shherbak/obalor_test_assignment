<?php

namespace App\Console\Commands\UploadCustomers;

use App\Infrastructure\Helpers\FilePathHelper;
use Illuminate\Console\Command;

class UploadCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers-uploader:upload {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload customers from given csv file.';

    private const SUCCESS_RESULT_CODE = 0;
    private const ERROR_RESULT_CODE = 1;
    private const ACCEPTABLE_FILE_EXT = 'csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $pathToCsv = $this->option('path');
        if ($errorMessage = FilePathHelper::validatePath($pathToCsv, self::ACCEPTABLE_FILE_EXT)) {
            $this->error($errorMessage);
            return self::ERROR_RESULT_CODE;
        }

        return self::SUCCESS_RESULT_CODE;
    }
}
