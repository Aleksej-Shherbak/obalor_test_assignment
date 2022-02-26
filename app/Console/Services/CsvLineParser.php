<?php

namespace App\Console\Services;

use App\Console\Dto\CsvLineParser\CsvCellDto;
use App\Console\Dto\CsvLineParser\CsvLineDto;
use App\Console\Dto\CsvLineParser\CsvLineParserResponse;

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

}
