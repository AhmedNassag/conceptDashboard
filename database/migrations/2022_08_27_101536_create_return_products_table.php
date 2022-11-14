<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_return_id')->nullable()->constrained('purchase_returns')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('purchase_product_id')->nullable()->constrained('purchase_products')->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('sub_quantity')->default(0);
            $table->double('price',25,2)->default(0);
            $table->double('sub_price',25,2)->default(0);
            $table->boolean('return_status')->default(false);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('return_products');
    }
}
