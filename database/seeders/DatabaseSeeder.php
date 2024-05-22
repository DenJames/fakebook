<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\WordFilter;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (User::count() < 10) {
            User::factory(500)->create();
        }

        if (WordFilter::count() <= 0) {
            $this->call(WordFilterSeeder::class);
        }
    }
}
