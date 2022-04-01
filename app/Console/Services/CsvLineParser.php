<?php

namespace App\Console\Services;

use App\Console\Dto\CsvLineParser\CsvCellDto;
use App\Console\Dto\CsvLineParser\CsvLineDto;
use App\Console\Dto\CsvLineParser\CsvLineParserResponse;
use App\Infrastructure\Helpers\ReflectionHelper;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Egulias\EmailValidator\Validation\RFCValidation;
use Illuminate\Support\Collection;

class CsvLineParser
{
    /**
     * Pars line from csv file with a customer's data to DTO.
     *
     * @param array<string> $rowLine
     * @param Collection<CsvCellDto> $positionsMap
     * @return CsvLineParserResponse
     */
    public function parsLine(array $rowLine, Collection $positionsMap): CsvLineParserResponse
    {
        $csvLineDto = new CsvLineDto();
        $currentCsvColumnName = '';

        foreach ($positionsMap as $positionsMapItem) {
            $currentCsvColumnName = $positionsMapItem->csvColumnName;

            // If the cell is empty we just pass it.
            if (!array_key_exists($positionsMapItem->position, $rowLine))
            {
                continue;
            }

            $currentCellValue = empty($rowLine[$positionsMapItem->position]) ? null : $rowLine[$positionsMapItem->position];

            if ($currentCellValue !== null && !$this->isValid($currentCellValue, $positionsMapItem->csvColumnName)) {
                return new CsvLineParserResponse(
                    response: null,
                    columnReasonOfFail: $positionsMapItem->csvColumnName,
                    isFailed: true);
            }

            $csvLineDto->{$positionsMapItem->csvColumnName} = $currentCellValue;
        }

        return new CsvLineParserResponse(
            response: $csvLineDto,
            columnReasonOfFail: null,
            isFailed: false);
    }

    /**
     * Build csv columns position map according to given header csv line.
     *
     * @param array<string> $headerLine
     * @return Collection<CsvCellDto> $positionsMap
     * @throws \Exception
     */
    public function buildPositionsMapFromCsvHeaderLine(array $headerLine): Collection
    {
        $expectedValues = ReflectionHelper::getClassPublicPropertiesNamesList(CsvLineDto::class);

        $givenValues = collect($headerLine);

        $positionsMap = collect([]);

        foreach ($expectedValues as $expectedValue) {
            $key = $givenValues->search(function(?string $x) use($expectedValue) { return $x === $expectedValue; });
            if ($key === false) {
                throw new \Exception("Column \"$expectedValue\" not found.");
            }

            $positionsMap->add(new CsvCellDto(position: $key, csvColumnName: $expectedValue));
        }

        return $positionsMap;
    }

    private function isValid(mixed $item, string $columnName): bool {
        if ($columnName === 'name' ) {
            return mb_strlen($item) <= 255;
        }

        if ($columnName === 'location' ) {
            return mb_strlen($item) <= 255;
        }

        if ($columnName === 'email') {
            $validator = new EmailValidator();
            $multipleValidations = new MultipleValidationWithAnd([
                new RFCValidation(),
                new DNSCheckValidation()
            ]);
            $isValidEmail = $validator->isValid($item, $multipleValidations);
            return mb_strlen($item) <= 255 && $isValidEmail;
        }

        if ($columnName === 'age') {
            return $item > 18 && $item < 99;
        }

        return false;
    }
}
