<?php

namespace Tests\Unit\Attributes;

use App\Infrastructure\Attributes\NumberBetween;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\ValidationException;

class NumberBetweenAttributeTest extends TestCase
{
    /**
     * @see NumberBetween accepts a number from the allowed range
     *
     * @return void
     */
    public function test_number_between_attribute_accepts_number_from_allowed_range()
    {
        new Test_NumberBetweenDto(number: 3);

        $this->assertTrue(true);
    }

    /**
     * @see NumberBetween accepts a number from the allowed range
     *
     * @return void
     */
    public function test_number_between_attribute_does_not_accept_number_out_of_allowed_range()
    {
        $this->expectException(ValidationException::class);

        new Test_NumberBetweenDto(number: 666);
    }
}

class Test_NumberBetweenDto extends DataTransferObject
{
    #[NumberBetween(1, 10)]
    public int $number;
}
