<?php

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
        $this->call([
            RestaurantsTableSeeder::class,
            PlatesTableSeeder::class,
            OrdersTableSeeder::class,
            CuisinesTableSeeder::class,
        ]);
    }
}
