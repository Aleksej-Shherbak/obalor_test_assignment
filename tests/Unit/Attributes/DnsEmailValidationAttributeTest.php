<?php

namespace Tests\Unit\Attributes;

use App\Infrastructure\Attributes\EmailDnsRecord;
use App\Infrastructure\Attributes\EmailFormat;
use PHPUnit\Framework\TestCase;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\ValidationException;

class DnsEmailValidationAttributeTest extends TestCase
{
    /**
     * @see EmailFormat attribute passes emails having DNS records.
     *
     * @return void
     */
    public function test_email_dns_record_attribute_accepts_valid_emails()
    {
        new Test_DnsEmailDto(email: 'aleksej.shherbak@yandex.ru');
        new Test_DnsEmailDto(email: 'aleksej.shherbak@mail.ru');

        $this->assertTrue(true);
    }

    /**
     * @see EmailDnsRecord attribute doesn't pass emails without DNS records.
     *
     * @return void
     */
    public function test_email_dns_record_attribute_does_not_accept_invalid_emails()
    {
        $this->expectException(ValidationException::class);

        new Test_DnsEmailDto(email: 'aleksej.shherbak@test.kek');
    }
}

class Test_DnsEmailDto extends DataTransferObject
{
    #[EmailDnsRecord]
    public string $email;
}
