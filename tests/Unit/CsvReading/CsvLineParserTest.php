<?php

namespace Tests\Unit\CsvReading;

use App\Console\Dto\CsvLineParser\CsvCellDto;
use App\Console\Services\CsvLineParser;
use PHPUnit\Framework\TestCase;

class CsvLineParserTest extends TestCase
{
    private CsvLineParser $csvLineParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->csvLineParser = app(CsvLineParser::class);
    }

    /**
     * Check @return void
     * @see CsvLineParser can pars array to appropriate Dto.
     *
     */
    public function test_csv_line_parser_parses_csv_line()
    {
        // arrange
        $line = ['2', 'Karly Schroeder', 'ivoibscomcast.net', 20, 'Afghanistan'];

        $positionMap = [
            new CsvCellDto(position: 0, csvColumnName: 'id'),
            new CsvCellDto(position: 1, csvColumnName: 'name'),
            new CsvCellDto(position: 2, csvColumnName: 'email'),
            new CsvCellDto(position: 3, csvColumnName: 'age'),
            new CsvCellDto(position: 4, csvColumnName: 'location'),
        ];

        // act
        $result = $this->csvLineParser->parsLine(line: $line, positionsMap: $positionMap);

        // assert
        $this->assertFalse($result->isFailed);
        $this->assertNull($result->columnReasonOfFail);
    }


    /**
     * Check @return void
     * @see CsvLineParser can build positions map.
     *
     */
    public function test_csv_line_parser_can_build_positions_map()
    {
        // arrange
        $line = ['id', 'name', 'email', 'age', 'location'];
        $numberOfColumns = 5;

        // act
        $result = $this->csvLineParser->buildPositionsMapFromCsvHeaderLine($line);

        // assert
        $this->assertCount($numberOfColumns, $result);
    }

    /**
     * Check @return void
     * @see CsvLineParser can build positions map.
     *
     */
    public function test_csv_line_parser_cant_build_positions_map_from_wrong_csv_header_line()
    {
        $this->expectException(\Exception::class);

        $line = ['id', 'name', 'email'];

        $this->csvLineParser->buildPositionsMapFromCsvHeaderLine($line);
    }
}
