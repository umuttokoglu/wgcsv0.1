<?php

namespace Database\Seeders;

use App\Models\Developer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DevelopersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developers = [
            ['name' => 'DEV1', 'efficiency' => 1],
            ['name' => 'DEV2', 'efficiency' => 2],
            ['name' => 'DEV3', 'efficiency' => 3],
            ['name' => 'DEV4', 'efficiency' => 4],
            ['name' => 'DEV5', 'efficiency' => 5],
        ];

        foreach ($developers as $dev) {
            Developer::create($dev);
        }
    }
}
