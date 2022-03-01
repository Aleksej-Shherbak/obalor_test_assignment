<?php

namespace Tests\Feature;

use App\Console\Dto\CsvLineParser\CsvLineDto;
use App\Console\Services\CsvLineProcessor;
use App\Models\CountryCode;
use App\Models\Customer;
use Carbon\Carbon;
use Tests\TestCase;

class CustomerLineProcessorTest extends TestCase
{
    private ?CsvLineProcessor $csvLineProcessor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->csvLineProcessor = app(CsvLineProcessor::class);
    }

    /**
     * Check if @see CsvLineProcessor can precess @see CsvLineDto and create a new DB record.
     *
     * @return void
     */
    public function test_csv_line_processor_can_process_line()
    {
        $expectedEmail = 'aleksej.shherbak@yandex.ru';
        $expectedAge = 20;
        $expectedBirthYear = Carbon::today('UTC')->subYears($expectedAge);
        $expectedLocation = 'New Caledonia';

        $csvLineDto = new CsvLineDto(email: $expectedEmail, age: $expectedAge, location: $expectedLocation);

        $countryCodes = CountryCode::all();
        /**
         * @var Customer
         */
        $customerArray = $this->csvLineProcessor->processLine($csvLineDto, $countryCodes);
        $this->assertEquals($customerArray['email'], $expectedEmail);
        $this->assertEquals($customerArray['birth_year'], $expectedBirthYear);
        $this->assertEquals($customerArray['location'], $expectedLocation);
    }
}
