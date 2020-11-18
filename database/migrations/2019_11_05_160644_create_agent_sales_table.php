<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('sale_id')->unsigned();
            $table->float('split')->default(0);
            $table->float('percent_of_sale',3, 1)->default(0);
            $table->float('commission')->default(0);
            $table->double('sale_credit');
            $table->double('blue_credit');
            $table->float('transaction_credit');
            $table->string('split_sale')->default('No');
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
        Schema::dropIfExists('sale_user');
    }
}
