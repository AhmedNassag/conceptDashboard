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
            'id'        => 1,
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
            'id'        => 2,
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
            'id'        => 3,
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


        $category4 = Category::create([
            'id'        => 4,
            'name'      => 'قطع إكسسوار',
            'parent_id' => 0,
            'status'    => 1
        ]);
        $category4->media()->create([
            'file_name' => 'spareAccessor.png',
            'file_size' => 1042431,
            'file_type' => 'image/png',
            'file_sort' => 1
        ]);
    }
}
