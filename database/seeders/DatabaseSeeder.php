<?php

namespace Database\Seeders;

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
        $this->call(DefaultUsersSeeder::class);
        $this->call(PlayerSpiritsSeeder::class);
        $this->call(ShopsSeeder::class);
    }
}
