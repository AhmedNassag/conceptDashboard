<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateproblemLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('description')->nullable();
            $table->text('address')->nullable();
            $table->bigInteger('seller_category_id')->unsigned();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->boolean('end_deal')->default(0);
            $table->foreign('seller_category_id')->references('id')->on('problem_seller_categories')->onDelete('cascade');
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
        Schema::dropIfExists('problem_leads');
    }
}
