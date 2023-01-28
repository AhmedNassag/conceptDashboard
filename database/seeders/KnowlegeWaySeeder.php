<?php

namespace Database\Seeders;

use App\Models\KnowledgeWay;
use Illuminate\Database\Seeder;

class KnowlegeWaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KnowledgeWay::create([
            'name' => 'Social Media',
        ]);
    }
}
