<?php

namespace Database\Seeders;

use App\Models\problemSellerCategory;
use Illuminate\Database\Seeder;


class problemSellerCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        problemSellerCategory::create([
            "name" => 'إستقبال أعطال',
            'isNumber' => 1
        ]);
        // problemSellerCategory::create([
        //     "name" => 'عدد أصناف المنتجات',
        //     'isNumber' => 1
        // ]);
        // problemSellerCategory::create([
        //     "name" => 'عدد الزيارات',
        //     'isNumber' => 1
        // ]);
        // problemSellerCategory::create([
        //     "name" => 'عدد العملاء',
        //     'isNumber' => 1
        // ]);
    }
}
