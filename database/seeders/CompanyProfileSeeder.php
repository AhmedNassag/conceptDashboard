<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = CompanyProfile::create([
            'name'      => 'concept company',
            'address'      => 'company address',
            'phone'      => 01000000000,
            'tax_card' => 1,
            'commercial_record' => 1,
        ]);
        $category->media()->create([
            'file_name' => 'companyProfile.png',
            'file_size' => 1042431,
            'file_type' => 'image/png',
            'file_sort' => 1
        ]);
    }
}
