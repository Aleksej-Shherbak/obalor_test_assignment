<?php

namespace App\Console\Services;

use App\Console\Dto\CsvLineParser\CsvCellDto;
use App\Console\Dto\CsvLineParser\CsvLineDto;
use App\Console\Dto\CsvLineParser\CsvLineParserResponse;
use App\Infrastructure\Helpers\ReflectionHelper;

class CsvLineParser
{
    /**
     * @param array<string> $line
     * @param array<CsvCellDto> $positionsMap
     * @return CsvLineParserResponse
     */
    public function parsLine(array $line, array $positionsMap): CsvLineParserResponse
    {
        $csvLineDto = new CsvLineDto();
        $currentCsvColumnName = '';

        try {
            foreach ($positionsMap as $positionsMapItem) {
                $currentCsvColumnName = $positionsMapItem->csvColumnName;

                // If the cell is empty we just pass it.
                if (!array_key_exists($positionsMapItem->position, $line))
                {
                    continue;
                }

                $csvLineDto->{$positionsMapItem->csvColumnName} = empty($currentCellValue) ? null : $currentCellValue;
            }

        } catch (\Exception | \Error $e) {
            return new CsvLineParserResponse(response: null, columnReasonOfFail: $currentCsvColumnName, isFailed: true);
        }
        return new CsvLineParserResponse(response: $csvLineDto, columnReasonOfFail: null, isFailed: false);
    }

    /**
     * @param array<string> $line
     * @return array<CsvCellDto> $positionsMap
     * @throws \Exception
     */
    public function buildPositionsMapFromCsvHeaderLine(array $line): array
    {
        $expectedValues = ReflectionHelper::getClassPublicPropertiesNamesList(CsvLineDto::class);

        $givenValues = collect($line);

        if ($expectedValues->count() !== $givenValues->count()) {
            throw new \Exception('Wrong columns quantity.');
        }

        $positionsMap = [];

        foreach ($expectedValues as $expectedValue) {
            $key = $givenValues->search(function(string $x) use($expectedValue) { return $x === $expectedValue; });
            if ($key === false) {
                throw new \Exception("Column $expectedValue not found.");
            }

            $positionsMap[] = new CsvCellDto(position: $key, csvColumnName: $expectedValue);
        }

        return $positionsMap;
    }
}
