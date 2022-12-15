<?php

namespace Database\Seeders;

use App\Models\followSellerCategory;
use Illuminate\Database\Seeder;


class followSellerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        followSellerCategory::create([
            "name" => 'متابعة ما بعد البيع',
            'isNumber' => 1
        ]);
        // followSellerCategory::create([
        //     "name" => 'عدد أصناف المنتجات',
        //     'isNumber' => 1
        // ]);
        // followSellerCategory::create([
        //     "name" => 'عدد الزيارات',
        //     'isNumber' => 1
        // ]);
        // followSellerCategory::create([
        //     "name" => 'عدد العملاء',
        //     'isNumber' => 1
        // ]);
    }
}
