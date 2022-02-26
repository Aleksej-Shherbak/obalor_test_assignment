<?php

namespace Tests\Unit\CsvReading;

use App\Console\Services\CsvReader;
use PHPUnit\Framework\TestCase;

class CsvFileReaderTest extends TestCase
{
    private CsvReader $csvReader;

    protected function setUp(): void
    {
        parent::setUp();

        $this->csvReader = app(CsvReader::class);
    }

    /**
     * Check does @see CsvReader return correct number of lines.
     *
     * @return void
     */
    public function test_csv_line_reader_returns_correct_number_of_lines()
    {
        $csvFileTotalNumberOfLines = 6;
        $pathToCsv = __DIR__ . '/random.csv';

        $totalNumberOfReceivedLines = count(iterator_to_array($this->csvReader->readCsvFile($pathToCsv)));

        $this->assertEquals($csvFileTotalNumberOfLines, $totalNumberOfReceivedLines);
    }


}
