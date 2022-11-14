<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Area::create([
            "name"=>"المرج",
            "province_id"=>1,
            'shipping_price' => 100
        ]);

        Area::create([
            "name"=>"عين شمس",
            "province_id"=>1,
            'shipping_price' => 100
        ]);
        Area::create([
            "name"=>"مدينة نصر",
            "province_id"=>1,
            'shipping_price' => 100
        ]);

        Area::create([
            "name"=>"الدقى",
            "province_id"=>2,
            'shipping_price' => 100
        ]);
        Area::create([
            "name"=>"الهرم",
            "province_id"=>2,
            'shipping_price' => 100
        ]);
        Area::create([
            "name"=>"فيصل",
            "province_id"=>2,
            'shipping_price' => 100
        ]);
    }
}
