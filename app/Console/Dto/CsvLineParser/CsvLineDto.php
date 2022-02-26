<?php

namespace App\Console\Dto\CsvLineParser;

use App\Infrastructure\Attributes\EmailDnsRecord;
use App\Infrastructure\Attributes\EmailFormat;
use App\Infrastructure\Attributes\NumberBetween;
use App\Infrastructure\Attributes\StringLength;
use Spatie\DataTransferObject\DataTransferObject;

class CsvLineDto extends DataTransferObject
{
    public int|null $id;

    #[StringLength(255)]
    public string|null $name;

    #[EmailDnsRecord]
    #[EmailFormat]
    #[StringLength(255)]
    public string|null $email;

    #[NumberBetween(18, 99)]
    public int|null $age;

    #[StringLength(255)]
    public string|null $location;
}
