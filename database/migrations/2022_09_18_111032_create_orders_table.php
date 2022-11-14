<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->double('discount')->default(0.00);
            $table->double('discount_offer')->default(0.00);
            $table->double('shippingPrice')->default(0.00);
            $table->double('tax')->default(0.00);
            $table->double('sub_total')->default(0.00);
            $table->double('total')->default(0.00);
            $table->string('currency')->default('EGP');
            $table->foreignId('order_status_id')->default(1)->constrained('order_statuses')->cascadeOnDelete();
            $table->foreignId('representative_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->boolean('is_shipping')->default(0);
            $table->boolean('is_online')->default(0);
            $table->integer('representative_status')->default(0);
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
        Schema::dropIfExists('orders');
    }
}
