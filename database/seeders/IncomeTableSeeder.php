<?php

namespace Database\Seeders;

use App\Models\Income;
use Illuminate\Database\Seeder;

class IncomeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Income::create([
            'name'=>'رأس المال',
        ]);

        Income::create([
            'name'=>'مرتجعات الشراء',
        ]);

        Income::create([
            'name'=>'المبيعات',
        ]);

        Income::create([
            'name'=>'حساب جارى شريك',
        ]);

        Income::create([
            'name'=>'قطع غيار وصيانات',
        ]);
    }
}
