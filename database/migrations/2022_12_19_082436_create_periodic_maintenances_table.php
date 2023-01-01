<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodicMaintenancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periodic_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('quantity')->nullable();
            $table->string('price')->nullable();
            $table->string('collector')->nullable();
<<<<<<< HEAD
            $table->date('next_maintenance');
=======
            $table->date('next_maintenance')->default(now())->nullable();
>>>>>>> 417c5a33e15b99f534eca336330fc5dcb5a6da41
            $table->text('note')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('periodic_maintenances');
    }
}
