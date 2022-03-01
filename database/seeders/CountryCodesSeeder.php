<?php

namespace Database\Seeders;

use App\Models\CountryCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountryCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countryCodes = [
            [
                'country' => 'Afghanistan',
                'alpha_3_code' => 'AFG',
            ],
            [
                'country' => 'Albania',
                'alpha_3_code' => 'ALB',
            ],
            [
                'country' => 'American Samoa',
                'alpha_3_code' => 'ASM',
            ],
            [
                'country' => 'Andorra',
                'alpha_3_code' => 'AND',
            ],
            [
                'country' => 'Anguilla',
                'alpha_3_code' => 'AIA',
            ],
            [
                'country' => 'Antarctica',
                'alpha_3_code' => 'ATA',
            ],
            [
                'country' => 'Antigua and Barbuda',
                'alpha_3_code' => 'ATG',
            ],
            [
                'country' => 'Argentina',
                'alpha_3_code' => 'ARG',
            ],
            [
                'country' => 'Armenia',
                'alpha_3_code' => 'ARM',
            ],
            [
                'country' => 'Australia',
                'alpha_3_code' => 'AUS',
            ],
            [
                'country' => 'Austria',
                'alpha_3_code' => 'AUT',
            ],
            [
                'country' => 'Azerbaijan',
                'alpha_3_code' => 'AZE',
            ],
            [
                'country' => 'Bahamas',
                'alpha_3_code' => 'BHS',
            ],
            [
                'country' => 'Bangladesh',
                'alpha_3_code' => 'BGD',
            ],
            [
                'country' => 'Belarus',
                'alpha_3_code' => 'BLR',
            ],
            [
                'country' => 'Burundi',
                'alpha_3_code' => 'BDI',
            ],
            [
                'country' => 'Cabo Verde',
                'alpha_3_code' => 'CPV',
            ],
            [
                'country' => 'Cambodia',
                'alpha_3_code' => 'KHM',
            ],
            [
                'country' => 'Cameroon',
                'alpha_3_code' => 'CMR',
            ],
            [
                'country' => 'Canada',
                'alpha_3_code' => 'CAN',
            ],
            [
                'country' => 'Central African Republic',
                'alpha_3_code' => 'CAF',
            ],
            [
                'country' => 'Chad',
                'alpha_3_code' => 'TCD',
            ],
            [
                'country' => 'Chile',
                'alpha_3_code' => 'CHL',
            ],
            [
                'country' => 'China',
                'alpha_3_code' => 'CHN',
            ],
            [
                'country' => 'Colombia',
                'alpha_3_code' => 'COL',
            ],
            [
                'country' => 'Comoros',
                'alpha_3_code' => 'COM',
            ],
            [
                'country' => 'Congo',
                'alpha_3_code' => 'COG',
            ],
            [
                'country' => 'Cook Islands',
                'alpha_3_code' => 'COK',
            ],
            [
                'country' => 'Croatia',
                'alpha_3_code' => 'HRV',
            ],
            [
                'country' => 'Cuba',
                'alpha_3_code' => 'CUB',
            ],
            [
                'country' => 'Cyprus',
                'alpha_3_code' => 'CYP',
            ],
            [
                'country' => 'Czechia',
                'alpha_3_code' => 'CZE',
            ],
            [
                'country' => 'Ecuador',
                'alpha_3_code' => 'ECU',
            ],
            [
                'country' => 'Denmark',
                'alpha_3_code' => 'CIV',
            ],
            [
                'country' => 'Djibouti',
                'alpha_3_code' => 'DJI',
            ],
            [
                'country' => 'Dominica',
                'alpha_3_code' => 'DMA',
            ],
        ];

        // TODO ask content department to represent the whole list in array like above
        CountryCode::insert($countryCodes);
    }
}










