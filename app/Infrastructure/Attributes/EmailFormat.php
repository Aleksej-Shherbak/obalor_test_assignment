<?php

namespace App\Infrastructure\Attributes;

use Attribute;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;

/**
 * The attribute validates emails according to the following RFCs list: 5321, 5322, 6530, 6531, 6532, 1035.
 * It's based on this library https://packagist.org/packages/egulias/email-validator
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class EmailFormat implements Validator
{
    public function validate(mixed $value): ValidationResult
    {
        $validator = new EmailValidator();
        $isValidEmail = $validator->isValid($value, new RFCValidation());

        if (!$isValidEmail) {
            return ValidationResult::invalid("Invalid email format {$value}");
        }

        return ValidationResult::valid();
    }
}
