<?php

namespace Tests\Unit\Attributes;

use App\Infrastructure\Attributes\EmailFormat;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\ValidationException;

class EmailValidationAttributeTest extends TestCase
{
    /**
     * @see EmailFormat attribute accepts valid by RFC emails.
     *
     * @return void
     */
    public function test_email_format_attribute_accepts_valid_emails()
    {
        // The examples are based on https://datatracker.ietf.org/doc/html/rfc5322#section-3.2.3
        new Test_EmailDto(email: 'aleksej.shherbak@yandex.ru');
        new Test_EmailDto(email: 'Ab!#def@example.com');
        new Test_EmailDto(email: 'Fred$ %Bloggs@example.com');
        new Test_EmailDto(email: 'Joe&\'Blow@example.com');
        new Test_EmailDto(email: 'Abc*def+auld@example.com');
        new Test_EmailDto(email: '"Fred- /Bloggs"@example.com');
        new Test_EmailDto(email: 'customer=department?shipping@example.com');
        new Test_EmailDto(email: '^A12345_@example.com');
        new Test_EmailDto(email: '`def!xyz|abc@example.com');
        new Test_EmailDto(email: 'some_name@example.com');
        new Test_EmailDto(email: '{some}name@example.com');
        new Test_EmailDto(email: '~some~name@example.com');
        new Test_EmailDto(email: '~some~name@домен.ру');

        $this->assertTrue(true);
    }

    /**
     * @see EmailFormat attribute doesn't accept valid by RFC emails.
     *
     * @return void
     */
    public function test_email_format_attribute_does_not_accept_invalid_emails()
    {
        $this->expectException(ValidationException::class);

        new Test_EmailDto(email: 'aleksej[shherbak]@yandex.ru');
    }
}

class Test_EmailDto extends DataTransferObject
{
    #[EmailFormat]
    public string $email;
}
