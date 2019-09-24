<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = ['pro_one', 'pro_two'];

        foreach ($products as $product) {
            \App\Product::create([
                'ar'                => ['name' => $product, 'description'   => $product . ' description'],
                'en'                => ['name' => $product, 'description'   => $product . ' description'],
                'category_id'       => 1,
                'purchase_price'    => 150,
                'sale_price'        => 200,
                'stock'             => 20
            ]);
        }
    }
}
