<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       DB::table('products')->delete();

       $data = [
            [
                'name' => 'Samsung Gaxaly S25 Ultra',
                'slug' => 'Samsung-Gaxaly-S25-Ultra',
                'description' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Maiores accusamus voluptates praesentium sequi iusto! Porro voluptates at nulla deserunt error est, sint fuga autem recusandae, corporis totam libero fugiat quaerat?',
                'price' => 39990.00,
                'image' => 'https://picsum.photos/seed/samsung-galaxy/800/800',
                'user_id' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        Product::insert($data);

         // ทำการเรียก ProductFactory มาโหลดใน ProductSeeder
         Product::factory(4999)->create();
    }
}
