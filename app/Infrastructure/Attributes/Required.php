<?php

namespace App\Infrastructure\Attributes;

use Attribute;
use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Required implements Validator
{
    public function validate(mixed $value): ValidationResult
    {
        if ($value === null) {
            return ValidationResult::invalid('Value is required.');
        }

        return ValidationResult::valid();
    }
}
