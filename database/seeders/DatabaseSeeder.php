<?php

namespace Database\Seeders;

use App\Models\Category; // Corrected namespace
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            AdminSeed::class,
            countrySeeder::class,
        ]);
    }
}
