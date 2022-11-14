<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRetuensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_retuens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('sub_quantity')->default(0);
            $table->foreignId('main_measurement_unit_id')->nullable()->constrained('measurement_units')->cascadeOnDelete();
            $table->foreignId('sub_measurement_unit_id')->nullable()->constrained('measurement_units')->cascadeOnDelete();
            $table->foreignId('order_details_id')->nullable()->constrained('order_details')->cascadeOnDelete();
            $table->foreignId('selling_method_id')->constrained('selling_methods')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->double('price',8,2)->default(0);
            $table->double('sub_price',8,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_retuens');
    }
}
