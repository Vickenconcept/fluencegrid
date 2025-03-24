<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            ['name' => 'Facebook'],
            ['name' => 'Instagram'],
            ['name' => 'YouTube'],
            ['name' => 'TikTok'],
        ];

        DB::table('platforms')->insert($platforms);
    }
}
