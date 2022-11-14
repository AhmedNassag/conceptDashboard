<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricingHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pricing_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('selling_method_id')->constrained('selling_methods')->cascadeOnDelete();
            $table->foreignId('measurement_unit_id')->constrained('measurement_units')->cascadeOnDelete();
            $table->double('price',20,2)->default(0);
            $table->double('profit',20,2)->default(0);
            $table->double('profit_percentage',20,2)->default(0);
            $table->integer('max_quantity')->default(0);
            $table->integer('less_quantity')->default(0);
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
        Schema::dropIfExists('pricing_histories');
    }
}
