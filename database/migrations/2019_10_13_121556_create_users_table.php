<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

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
            $table->bigIncrements('id');
            $table->string('agent_name');
            $table->string('phone', 12)->nullable(true);
            $table->string('email')->unique()->nullable(true);
            $table->string('password')->default('password123');
            $table->string('api_token', 80)->unique()->nullable(true);
            $table->boolean('isAdmin')->nullable(true)->default(false);
            $table->boolean('isManager')->nullable(true)->default(false);
            $table->string('picture_url')->nullable(true);
            $table->integer('membership')->default(0);
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
