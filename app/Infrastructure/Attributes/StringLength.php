<?php

namespace App\Infrastructure\Attributes;

use Attribute;
use Spatie\DataTransferObject\Validation\ValidationResult;
use Spatie\DataTransferObject\Validator;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class StringLength implements Validator
{
    public function __construct(
        private string $max
    )
    {
    }

    public function validate(mixed $value): ValidationResult
    {
        // Don't react if null.
        if ($value === null) {
            return ValidationResult::valid();
        }

        if (mb_strlen($value) > $this->max) {
            return ValidationResult::invalid("String should be shorter than {$this->max}.");
        }

        return ValidationResult::valid();
    }
}
