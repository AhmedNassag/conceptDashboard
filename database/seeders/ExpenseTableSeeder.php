<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Seeder;

class ExpenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Expense::create([
           'name'=>'شراء منتجات'
        ]);

        Expense::create([
           'name'=>'حساب جارى شريك'
        ]);
    }
}
