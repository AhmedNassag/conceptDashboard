<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = Category::create([
            'name'      => 'فلاتر',
            'parent_id' => 0,
            'status'    => 1
        ]);
        $category->media()->create([
            'file_name' => 'filter.png',
            'file_size' => 1042431,
            'file_type' => 'image/png',
            'file_sort' => 1
        ]);


        $category2 = Category::create([
            'name'      => 'شمع',
            'parent_id' => 0,
            'status'    => 1
        ]);
        $category2->media()->create([
            'file_name' => 'wax.png',
            'file_size' => 1042431,
            'file_type' => 'image/png',
            'file_sort' => 1
        ]);


        $category3 = Category::create([
            'name'      => 'قطع غيار',
            'parent_id' => 0,
            'status'    => 1
        ]);
        $category3->media()->create([
            'file_name' => 'sparePart.png',
            'file_size' => 1042431,
            'file_type' => 'image/png',
            'file_sort' => 1
        ]);
    }
}
