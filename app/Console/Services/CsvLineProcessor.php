<?php

namespace App\Console\Services;

use App\Console\Dto\CsvLineParser\CsvLineDto;
use App\Models\CountryCode;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CsvLineProcessor
{
    private const NAME_INDEX = 0;
    private const NAME_SURNAME_INDEX = 1;


    /**
     * Method prepares an array from a csv line that is ready to be used in the bulk insert.
     *
     * @param CsvLineDto $csvLineDto
     * @param Collection<CountryCode> $countryCodes
     * @return ?array
     */
    public function processLine(CsvLineDto $csvLineDto, Collection $countryCodes): ?array
    {
        $customerArray = [];
        $customerArray['email'] = $csvLineDto->email;

        if ($csvLineDto->age !== null) {
            $customerArray['birth_year'] = $this->calculateCustomerBirthYear($csvLineDto->age);
        }

        if ($csvLineDto->name !== null) {
            $customerArray['name'] = $this->retrieveNamePart($csvLineDto->name, self::NAME_INDEX);
            $customerArray['surname'] = $this->retrieveNamePart($csvLineDto->name, self::NAME_SURNAME_INDEX);
        } else {

            $customerArray['name'] = null;
            $customerArray['surname'] = null;
        }

        $customerArray['location'] = $csvLineDto->location;

        if ($csvLineDto->location !== null) {
            $customerArray['country_code'] = $this->getCustomerCountryCode($csvLineDto->location, $countryCodes);
        } else {
            $customerArray['country_code'] = env('UNKNOWN_LOCATION_PLACEHOLDER');
        }

        return $customerArray;
    }

    private function retrieveNamePart(string $name, int $index): ?string
    {
        $res = preg_split('~\s+~', $name);

        if (array_key_exists($index, $res)) {
            return $res[$index];
        }
        return null;
    }

    private function calculateCustomerBirthYear(int $age): string
    {
        return Carbon::today('UTC')->subYears($age);
    }

    /**
     * @param string $customerLocale
     * @param Collection<CountryCode> $countryCodes
     * @return string|null
     */
    private function getCustomerCountryCode(string $customerLocale, Collection $countryCodes): ?string
    {
        /**
         * @var CountryCode
         */
        $res = $countryCodes->first(function (CountryCode $x) use ($customerLocale) {
            return $x->country === $customerLocale;
        });

        if ($res === null) {
            return null;
        }

        return $res->alpha_3_code;
    }

}
