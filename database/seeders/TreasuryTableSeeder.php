<?php

namespace Database\Seeders;

use App\Models\Treasury;
use Illuminate\Database\Seeder;

class TreasuryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Treasury::create([
            'name' => 'الخزنة الرئيسية'
        ]);
    }
}
