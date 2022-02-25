<?php

namespace App\Console\Commands\UploadCustomers\Dto;

use App\Infrastructure\Attributes\EmailDnsRecord;
use App\Infrastructure\Attributes\EmailFormat;
use App\Infrastructure\Attributes\NumberBetween;
use App\Infrastructure\Attributes\StringLength;
use Spatie\DataTransferObject\DataTransferObject;

class CsvLineDto extends DataTransferObject
{
    #[StringLength(255)]
    public string $name;

    #[StringLength(255)]
    public string $surname;

    #[EmailDnsRecord]
    #[EmailFormat]
    #[StringLength(255)]
    public string $email;

    #[NumberBetween(18, 99)]
    public int $age;

    #[StringLength(255)]
    public string $location;

    #[StringLength(255)]
    public string $countryCode;
}
