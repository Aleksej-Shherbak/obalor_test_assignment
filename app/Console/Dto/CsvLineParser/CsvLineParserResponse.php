<?php

namespace App\Console\Dto\CsvLineParser;

class CsvLineParserResponse
{
    public function __construct(
        public ?CsvLineDto $response,
        public ?string     $columnReasonOfFail,
        public bool        $isFailed,
    )
    {
    }

}
