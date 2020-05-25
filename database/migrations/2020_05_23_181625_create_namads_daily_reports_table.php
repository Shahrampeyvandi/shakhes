<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNamadsDailyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('namads_daily_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('namad_id');
            $table->integer('last_price_value')->nullable();
            $table->integer('last_price_change')->nullable();
            $table->integer('last_price_percent')->nullable();
            $table->string('last_price_status')->nullable();
            $table->integer('final_price_value')->nullable();
            $table->integer('final_price_change')->nullable();
            $table->integer('final_price_percent')->nullable();
            $table->string('final_price_status')->nullable();
            $table->integer('trades_date')->nullable();
            $table->integer('trades_count')->nullable();
            $table->integer('trades_volume')->nullable();
            $table->integer('trades_value')->nullable();
            $table->integer('trades_medium')->nullable();
            $table->integer('prices_yesterday')->nullable();
            $table->integer('prices_first')->nullable();
            $table->integer('prices_low')->nullable();
            $table->integer('prices_high')->nullable();
            $table->integer('buy_count')->nullable();
            $table->integer('buy_volume')->nullable();
            $table->integer('buy_price')->nullable();
            $table->integer('sale_count')->nullable();
            $table->integer('sale_volume')->nullable();
            $table->integer('sale_price')->nullable();
            $table->integer('market_value')->nullable();
            $table->integer('property_today')->nullable();
            $table->integer('property_realty')->nullable();
            $table->integer('last_capital')->nullable();
            $table->integer('debt')->nullable();
            $table->integer('salary')->nullable();
            $table->integer('income')->nullable();
            $table->integer('ttm')->nullable();
            $table->integer('buy_vol_person')->nullable();
            $table->integer('buy_vol_legal')->nullable();
            $table->integer('sale_vol_person')->nullable();
            $table->integer('sale_vol_legal')->nullable();
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
        Schema::dropIfExists('namads_daily_reports');
    }
}
