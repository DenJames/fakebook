<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WordFilterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badWords = [
            'abuse', 'asshole', 'bastard', 'bitch', 'bullshit', 'cunt',
            'damn', 'dick', 'faggot', 'fuck', 'motherfucker', 'nigger',
            'nigga', 'piss', 'prick', 'pussy', 'shit', 'slut', 'whore',
        ];

        foreach ($badWords as $word) {
            DB::table('word_filters')->insert(['word' => $word, 'replacement' => str_repeat('*', count(count_chars($word, 1)))]);
        }
    }
}
