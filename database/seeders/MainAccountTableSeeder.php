<?php

namespace Database\Seeders;

use App\Models\MainAccount;
use Illuminate\Database\Seeder;

class MainAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MainAccount::create([
            "name" => 'الأصول',
            'debit'=>0
        ]);
        MainAccount::create([
            "name" => 'الخصوم',
            'debit'=>1
        ]);
        MainAccount::create([
            "name" => 'المصروفات',
            'debit'=>1
        ]);
        MainAccount::create([
            "name" => 'الأیرادات',
            'debit'=>0
        ]);
    }
}
