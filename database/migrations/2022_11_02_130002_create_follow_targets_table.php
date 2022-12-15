<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatefollowTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follow_targets', function (Blueprint $table) {
            $table->id();
            $table->integer('from')->default(0);
            $table->integer('to')->default(0);
            $table->double('amount',20,2)->default(0);
            $table->double('percent',8,2)->default(0);
            $table->bigInteger('seller_category_id')->unsigned()->nullable();
            $table->foreign('seller_category_id')->references('id')->on('follow_seller_categories')->onDelete('cascade');
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
        Schema::dropIfExists('follow_targets');
    }
}
