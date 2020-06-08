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
            $table->bigInteger('last_price_value')->nullable();
            $table->bigInteger('last_price_change')->nullable();
            $table->float('last_price_percent',25,2)->nullable();
            $table->string('last_price_status')->nullable();
            $table->bigInteger('final_price_value')->nullable();
            $table->bigInteger('final_price_change')->nullable();
            $table->float('final_price_percent',25,2)->nullable();
            $table->string('final_price_status')->nullable();
            $table->string('trades_date')->nullable();
            $table->bigInteger('trades_count')->nullable();
            $table->bigInteger('trades_volume')->nullable();
            $table->bigInteger('trades_value')->nullable();
            $table->float('trades_medium',25,2)->nullable();
            $table->bigInteger('prices_yesterday')->nullable();
            $table->bigInteger('prices_first')->nullable();
            $table->bigInteger('prices_low')->nullable();
            $table->bigInteger('prices_high')->nullable();
            $table->bigInteger('buy_count')->nullable();
            $table->bigInteger('buy_volume')->nullable();
            $table->bigInteger('buy_price')->nullable();
            $table->bigInteger('sale_count')->nullable();
            $table->bigInteger('sale_volume')->nullable();
            $table->bigInteger('sale_price')->nullable();
            $table->bigInteger('market_value')->nullable();
            $table->bigInteger('property_today')->nullable();
            $table->bigInteger('property_realty')->nullable();
            $table->bigInteger('last_capital')->nullable();
            $table->bigInteger('debt')->nullable();
            $table->bigInteger('salary')->nullable();
            $table->bigInteger('income')->nullable();
            $table->bigInteger('ttm')->nullable();
            $table->bigInteger('buy_vol_person')->nullable();
            $table->bigInteger('buy_vol_legal')->nullable();
            $table->bigInteger('sale_vol_person')->nullable();
            $table->bigInteger('sale_vol_legal')->nullable();
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
