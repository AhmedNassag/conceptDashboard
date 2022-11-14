<?php

namespace Database\Seeders;

use App\Models\CreditCapacity;
use Illuminate\Database\Seeder;

class CreditCapacitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CreditCapacity::create([
            'start_amount' => 10000,
            'end_amount' => 20000,
            'amount' => 1000,
            'status' => "ضعيفة",
        ]);

        CreditCapacity::create([
            'start_amount' => 20000,
            'end_amount' => 30000,
            'amount' => 2000,
            'status' => "جيدة",
        ]);

        CreditCapacity::create([
            'start_amount' => 30000,
            'end_amount' => 40000,
            'amount' => 3000,
            'status' => "جيدة جدا",
        ]);

        CreditCapacity::create([
            'start_amount' => 40000,
            'end_amount' => 50000,
            'amount' => 4000,
            'status' => "ممتازة",
        ]);
    }
}
