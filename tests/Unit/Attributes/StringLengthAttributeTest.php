<?php

namespace Tests\Unit\Attributes;

use App\Infrastructure\Attributes\StringLength;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\ValidationException;

class StringLengthAttributeTest extends TestCase
{
    /**
     * @see StringLength accepts a string of appropriate length
     *
     * @return void
     */
    public function test_string_length_attribute_accepts_string_of_appropriate_length()
    {
        new Test_StringLengthDto(str: 'лол');

        $this->assertTrue(true);
    }

    /**
     * @see StringLength does not accept a string exceeding the appropriate length
     *
     * @return void
     */
    public function test_string_length_attribute_does_not_accept_string_exceeding_length()
    {
        $this->expectException(ValidationException::class);

        new Test_StringLengthDto(str: 'ла ла ла');
    }
}

class Test_StringLengthDto extends DataTransferObject
{
    #[StringLength(3)]
    public string $str;
}
