<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examination_record_id')->nullable()->constrained('examination_records')->cascadeOnDelete();
            $table->foreignId('product_status_id')->default(1)->constrained('product_statuses')->cascadeOnDelete();
            $table->foreignId('sub_category_id')->default(Null)->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('purchase_product_id')->nullable()->constrained('purchase_products')->cascadeOnDelete();
            $table->foreignId('main_measurement_unit_id')->constrained('measurement_units')->cascadeOnDelete();
            $table->foreignId('sub_measurement_unit_id')->constrained('measurement_units')->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('sub_quantity')->default(0);
            $table->integer('sub_quantity_order')->default(0);
            $table->date('expiry_date')->nullable();
            $table->date('production_date')->nullable();
            $table->integer('count_unit')->default(0);
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
        Schema::dropIfExists('store_products');
    }
}
