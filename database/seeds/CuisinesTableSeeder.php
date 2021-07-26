<?php

use Illuminate\Database\Seeder;
use App\Cuisine;

class CuisinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $types = [
            'Italian',
            'Japanese',
            'Chinese',
            'Greek',
            'Mexican',
            'Indian',
            'Pizza',
            'American'
        ];

        foreach($types as $type) {

            $new_cuisine = new Cuisine();

            $new_cuisine->type = $type;

            $new_cuisine->save();
        }




    }
}
