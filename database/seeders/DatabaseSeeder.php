<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Selection;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Selection::factory(10)->create();
//         \App\Models\Player::factory(10)->create();
//
//         \App\Models\Player::factory()->create([
//             'name' => 'Test Player',
//             'email' => 'test@example.com',
//         ]);
    }
}
