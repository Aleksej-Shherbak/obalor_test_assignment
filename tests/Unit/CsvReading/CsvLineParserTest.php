<?php

namespace Tests\Unit\CsvReading;

use App\Console\Dto\CsvLineParser\CsvCellDto;
use App\Console\Dto\CsvLineParser\CsvLineDto;
use App\Console\Services\CsvLineParser;
use App\Infrastructure\Helpers\ReflectionHelper;
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
        $expectedEmail = 'ivoibscomcast.net';
        $expectedAge = 20;
        $expectedCountry = 'Afghanistan';
        $expectedName = 'Karly Schroeder';
        $expectedId = '2';

        $line = [$expectedId, $expectedName, $expectedEmail, $expectedAge, $expectedCountry];

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
        $this->assertEquals($expectedEmail, $result->response->email);
        $this->assertEquals($expectedAge, $result->response->age);
        $this->assertEquals($expectedCountry, $result->response->location);
        $this->assertEquals($expectedName, $result->response->name);
        $this->assertEquals($expectedId, $result->response->id);
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
        $expectedNumberOfColumns = ReflectionHelper::getClassPublicPropertiesNamesList(CsvLineDto::class)->count();

        // act
        $result = $this->csvLineParser->buildPositionsMapFromCsvHeaderLine($line);

        // assert
        $this->assertCount($expectedNumberOfColumns, $result);
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
