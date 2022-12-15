<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateproblemLeadCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_lead_comments', function (Blueprint $table) {
            $table->id();
            $table->text('comment');
            $table->bigInteger('employee_id')->unsigned();
            $table->bigInteger('lead_id')->unsigned();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('lead_id')->references('id')->on('problem_leads')->onDelete('cascade');
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
        Schema::dropIfExists('problem_lead_comments');
    }
}
