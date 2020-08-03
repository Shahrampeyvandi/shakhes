<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolumeTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volume_trades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('namad_id');
            $table->string('trade_vol');
            $table->string('month_avg');
            $table->string('volume_ratio')->nullable();
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
        Schema::dropIfExists('volume_trades');
    }
}
