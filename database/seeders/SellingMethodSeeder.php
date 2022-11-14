<?php

namespace Database\Seeders;

use App\Models\SellingMethod;
use Illuminate\Database\Seeder;

class SellingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SellingMethod::create([
            'name' => 'قطاعي',
            'order_amount' => 1000
        ]);

        SellingMethod::create([
            'name' => 'جملة',
            'order_amount' => 1000
        ]);

        SellingMethod::create([
            'name' => 'توزيع',
            'order_amount' => 1000
        ]);
    }
}
