<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'close' => 0,
            'phone' => "01123456789",
            'wats_app' => "01123456789",
            'facebook' => "https://ar-ar.facebook.com/"
        ]);
    }
}
