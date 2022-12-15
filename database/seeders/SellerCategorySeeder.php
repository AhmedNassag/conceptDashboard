<?php

namespace Database\Seeders;

use App\Models\SellerCategory;
use Illuminate\Database\Seeder;


class SellerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SellerCategory::create([
            "name" => 'فلوس',
            'isNumber' => 1
        ]);
//        SellerCategory::create([
//            "name" => 'عدد أصناف المنتجات',
//            'isNumber' => 1
//        ]);
//        SellerCategory::create([
//            "name" => 'عدد الزيارات',
//            'isNumber' => 1
//        ]);
//        SellerCategory::create([
//            "name" => 'عدد العملاء',
//            'isNumber' => 1
//        ]);
    }
}
