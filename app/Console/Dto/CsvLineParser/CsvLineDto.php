<?php

namespace App\Console\Dto\CsvLineParser;

use Spatie\DataTransferObject\DataTransferObject;

class CsvLineDto extends DataTransferObject
{
    public ?string $name;
    public ?string $email;
    public ?int $age;
    public ?string $location;
}
