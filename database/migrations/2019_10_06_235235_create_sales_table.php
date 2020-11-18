<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('type', 50)->default('Buyer');
            $table->char('agent_name', 100);
            $table->char('client_name', 200)->nullable(true);
            $table->double('sale_price', 12, 2);
            $table->char('address', 200)->nullable(true);
            $table->char('city', 200)->nullable(true);
            $table->date('closing_date');
            $table->double('total_commission', 16, 2)->default(0);
            $table->double('transaction_fee', 16, 2)->default(0);
            $table->double('blue_profit', 12, 2)->default(0);
            $table->char('title_choice')->default('Other');
            $table->char('mortgage_choice')->default('Other');
            $table->text('notes')->nullable(true);
            $table->timestamps();
            $table->char('user', 200)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
