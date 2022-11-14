<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusTableSeerer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatus::create([
           'name' => 'طلب جديد'
        ]);

        OrderStatus::create([
           'name' => 'تم تأكيد الطلب'
        ]);

        OrderStatus::create([
           'name' => 'فى مرحلة التجهيز'
        ]);

        OrderStatus::create([
           'name' => 'خرج الطلب مع المندوب'
        ]);

        OrderStatus::create([
           'name' => 'تسليم ناجح'
        ]);

        OrderStatus::create([
           'name' => 'مرتجع كلى'
        ]);

        OrderStatus::create([
           'name' => 'مرتجع جزئي'
        ]);

        OrderStatus::create([
            'name' => 'مؤجل'
        ]);
    }
}
