<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Category::create([
            'ar'    =>  ['name' => 'cat ar'],
            'en'    =>  ['name' => 'cat en'],
        ]);
    }
}
