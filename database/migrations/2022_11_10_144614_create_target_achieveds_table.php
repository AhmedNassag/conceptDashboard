<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTargetAchievedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_achieveds', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->bigInteger('seller_category_id')->unsigned();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->integer('count')->default(0);
            $table->double('amount',20,2)->default(0);

            $table->foreign('seller_category_id')->references('id')->on('seller_categories')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
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
        Schema::dropIfExists('target_achieveds');
    }
}
