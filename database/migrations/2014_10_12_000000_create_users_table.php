<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->string('code')->nullable();
            $table->string('auth_id')->default(2);
            $table->string('user_code')->nullable();
            $table->text('role_name')->nullable();
            $table->text('type')->default('client')->nullable();
            $table->text('share_code')->nullable()->default(Null);
            $table->text('firebase_token')->nullable();
            $table->foreignId('knowledge_way_id')->nullable()->constrained('knowledge_ways')->cascadeOnDelete();
            $table->boolean('status')->default(true);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
