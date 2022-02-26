<?php

namespace App\Console\Dto\CsvLineParser;

class CsvCellDto
{
    public function __construct(
        public int $position,
        public string $csvColumnName,
    )
    {
    }
}
