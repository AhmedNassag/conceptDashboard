<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilterWaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_waxes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('price',20,2)->default(0);
            $table->string('model')->nullable();
            $table->string('type')->nullable();
            $table->string('origin')->nullable();
            $table->string('period')->nullable();
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
        Schema::dropIfExists('filter_waxes');
    }
}
