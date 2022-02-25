<?php

namespace App\Console\Commands\UploadCustomers;

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
    private const ACCESSIBLE_FILE_EXT = 'csv';

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
        if (!$this->validatePath($pathToCsv)) {
            return self::ERROR_RESULT_CODE;
        }

        return self::SUCCESS_RESULT_CODE;
    }

    private function validatePath(string|null $path) : bool {
        if (empty($path)) {
            $this->error('--path argument is required.');
            return false;
        }
        if (!file_exists($path)) {
            $this->error('Invalid file path.');
            return false;
        }
        if (pathinfo($path, PATHINFO_EXTENSION) !== self::ACCESSIBLE_FILE_EXT) {
            $this->error("Only files with " . self::ACCESSIBLE_FILE_EXT . " extension are allowed.");
            return false;
        }

        return true;
    }
}
