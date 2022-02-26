<?php

namespace App\Console\Services;

use App\Infrastructure\Helpers\FilePathHelper;
use InvalidArgumentException;

class CsvReader
{
    /**
     * Return generator of arrays where each array represents a csv line.
     *
     * @param string $filePath
     * @param string $delimiter
     * @param bool $skipFirstLine
     * @return \Generator
     */
    public function readCsvFile(string $filePath, string $delimiter = ',', bool $skipFirstLine = false): \Generator
    {
        if ($validationPathErrorMessage = FilePathHelper::validatePath($filePath, 'csv')) {
            throw new InvalidArgumentException($validationPathErrorMessage);
        }

        $fileHandle = fopen($filePath, 'r');

        // If skip the first line, then read one line into emptiness.
        if ($skipFirstLine) {
            fgetcsv($fileHandle);
        }

        while (($row = fgetcsv($fileHandle, separator: $delimiter)) !== false) {
            yield $row;
        }
        fclose($fileHandle);
    }
}
