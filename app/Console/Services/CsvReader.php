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
     * @return \Generator<array<string>>
     */
    public function readCsvFile(string $filePath, string $delimiter = ','): \Generator
    {
        $this->validatePath($filePath);

        $fileHandle = fopen($filePath, 'r');

        // Skip the header.
        fgetcsv($fileHandle, separator: $delimiter);
        while (($row = fgetcsv($fileHandle, separator: $delimiter)) !== false) {
            yield $row;
        }
        fclose($fileHandle);
    }

    /**
     * Read header line in csv file by given path.
     *
     * @param string $filePath
     * @param string $delimiter
     * @return array<string>|null
     */
    public function readHeaderLine(string $filePath, string $delimiter = ','): ?array
    {
        $this->validatePath($filePath);

        $fileHandle = fopen($filePath, 'r');
        $res = fgetcsv($fileHandle, separator: $delimiter);
        fclose($fileHandle);

        if (is_array($res)) {
            return $res;
        }
        return null;
    }

    private function validatePath(string $filePath) {
        if ($validationPathErrorMessage = FilePathHelper::validatePath($filePath, 'csv')) {
            throw new InvalidArgumentException($validationPathErrorMessage);
        }
    }
}
