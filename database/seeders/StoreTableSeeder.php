<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Store::create([
            'name' => 'المخزن الرئيس',
            'phone' => '01234567890',
            'province_id' => 1,
            'area_id' => 1,
            'main' => 1,
        ]);
    }
}
