<?php

namespace Database\Seeders;

use Unusualdope\FilamentModelTranslatable\Models\FmtLanguage;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        FmtLanguage::create(
            [
                'iso_code' => 'en',
                'name' => 'English',
            ]
        );
        FmtLanguage::create(
            [
                'iso_code' => 'it',
                'name' => 'Italiano',
            ]
        );

    }
}
