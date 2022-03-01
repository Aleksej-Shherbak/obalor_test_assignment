<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string country
 * @property string alpha_3_code
 */
class CountryCode extends Model
{
    use HasFactory;
}
