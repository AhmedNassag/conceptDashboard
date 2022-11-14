<?php

namespace Database\Seeders;

use App\Models\Suggestion;
use Illuminate\Database\Seeder;

class SuggestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Suggestion::create([
            'name'=>'اقتراح سعر منتج معين'
        ]);

        Suggestion::create([
            'name'=>'نقص منتج معين'
        ]);

        Suggestion::create([
            'name'=>'تطبيق على مستوى التواصل'
        ]);

        Suggestion::create([
            'name'=>'تعليق على البرنامج'
        ]);

        Suggestion::create([
            'name'=>'اقتراحات اخرى'
        ]);
    }
}
