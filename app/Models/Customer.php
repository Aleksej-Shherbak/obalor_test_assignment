<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string name - customer's name.
 * @property string surname - customer's surname.
 * @property string email - customer's email.
 * @property Carbon birth_year - customer's birth year (not birthday date!!!).
 * @property string location - customer's location.
 * @property string country_code - code of the customer's country. Format https://www.iban.com/country-codes.
 * Customer model.
 */
class Customer extends Model
{
    use HasFactory;

    public function usesTimestamps(): bool
    {
        return false;
    }


}
