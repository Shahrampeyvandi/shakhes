<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoldingsNamadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('holdings_namads', function (Blueprint $table) {
            $table->unsignedBigInteger('namad_id');
            $table->unsignedBigInteger('holding_id');
            $table->integer('amount_percent');
            $table->integer('amount_value');
            $table->integer('change');
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
        Schema::dropIfExists('holdings_namads');
    }
}
