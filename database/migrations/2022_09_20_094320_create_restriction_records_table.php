<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestrictionRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restriction_records', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_add')->default(0);
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('sub_account_id')->unsigned()->nullable();
            $table->bigInteger('daily_restriction_id')->unsigned()->nullable();
            $table->text('description');
            $table->double('amount',30,2)->default(0);
            $table->boolean('debit')->default(0);
            $table->date('date');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('sub_account_id')->references('id')->on('sub_accounts')->onDelete('cascade');
            $table->foreign('daily_restriction_id')->references('id')->on('daily_restrictions')->onDelete('cascade');

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
        Schema::dropIfExists('restriction_records');
    }
}
