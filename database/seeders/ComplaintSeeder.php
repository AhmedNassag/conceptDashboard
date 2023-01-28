<?php

namespace Database\Seeders;

use App\Models\Complaint;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Complaint::create([
            'name'=>'شكوى سعر منتج معين'
        ]);

        Complaint::create([
            'name'=>'نقص منتج معين'
        ]);

        Complaint::create([
            'name'=>'تطبيق على مستوى التواصل'
        ]);

        Complaint::create([
            'name'=>'تعليق على البرنامج'
        ]);

        Complaint::create([
            'name'=>'شكاوى اخرى'
        ]);
    }
}
