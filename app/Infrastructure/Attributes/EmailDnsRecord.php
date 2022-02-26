<?php

namespace App\Infrastructure\Attributes;

use Attribute;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;

/**
 * Check if given email has DNS record.
 * It's based on this library https://packagist.org/packages/egulias/email-validator.
 * This attribute will make HTTP requests. Be careful using it on big data collections.
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class EmailDnsRecord implements Validator
{
    public function validate(mixed $value): ValidationResult
    {
        // Don't react if null.
        if ($value === null) {
            return ValidationResult::valid();
        }

        $validator = new EmailValidator();
        $isValidEmail = $validator->isValid($value, new DNSCheckValidation());

        if (!$isValidEmail) {
            return ValidationResult::invalid("Email {$value} does not have DNS record.");
        }

        return ValidationResult::valid();
    }
}
