<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description');
            $table->unsignedBigInteger('barcode')->unique()->nullable();
            $table->integer('Re_order_limit');
            $table->integer('maximum_product');
            $table->text('image')->nullable();
            $table->string('shipping')->nullable();
            $table->string('guarantee')->nullable();
            $table->string('guarantee_type')->nullable();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('sub_category_id')->default(Null)->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('company_id')->default(Null)->nullable()->constrained('companies')->cascadeOnDelete();
            $table->foreignId('main_measurement_unit_id')->constrained('measurement_units')->cascadeOnDelete();
            $table->foreignId('sub_measurement_unit_id')->constrained('measurement_units')->cascadeOnDelete();
            $table->integer('count_unit')->default(0);
            $table->double('points')->default(0)->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('sell_app')->default(true);
            $table->integer('wax_id')->nullable();
            $table->integer('part_id')->nullable();
            $table->integer('accessor_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
