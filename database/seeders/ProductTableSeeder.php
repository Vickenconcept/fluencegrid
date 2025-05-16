<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductTableSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['product_id' => 416913, 'name' => 'App Features Only', 'funnel' => 'FE'],

            ['product_id' => 417107, 'name' => 'Full Access', 'funnel' => 'OTO1'],
            ['product_id' => 417109, 'name' => 'Full Access', 'funnel' => 'OTO1'],

            ['product_id' => 417119, 'name' => 'DFY Influencer Marketing Agency', 'funnel' => 'OTO4'],
            ['product_id' => 417121, 'name' => 'DFY Influencer Marketing Agency', 'funnel' => 'OTO4'],

            ['product_id' => 417123, 'name' => 'Affiliate Marketing Training', 'funnel' => 'OTO5'],
            ['product_id' => 417127, 'name' => 'Affiliate Marketing Training', 'funnel' => 'OTO5'],

            ['product_id' => 417129, 'name' => 'Reseller Access', 'funnel' => 'OTO6'],
            ['product_id' => 417131, 'name' => 'Reseller Access', 'funnel' => 'OTO6'],

            ['product_id' => 416977, 'name' => 'Ultimate Full Access', 'funnel' => 'Bundle'],
        ];



        foreach ($products as $product) {
            Product::firstOrCreate(
                ['product_id' => $product['product_id']], // Check for existence by product_id
                ['name' => $product['name'], 'funnel' => $product['funnel']] // Data to create if product_id doesn't exist
            );
        }
    }
}
