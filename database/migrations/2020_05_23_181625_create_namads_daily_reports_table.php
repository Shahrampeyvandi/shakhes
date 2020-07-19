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
            $table->text('lastbuys')->nullable();
            $table->text('lastsells')->nullable();
            $table->bigInteger('personbuy')->nullable();
            $table->bigInteger('legalbuy')->nullable();
            $table->bigInteger('personsell')->nullable();
            $table->bigInteger('legalsell')->nullable();
            $table->bigInteger('personbuycount')->nullable();
            $table->bigInteger('legalbuycount')->nullable();
            $table->bigInteger('personsellcount')->nullable();
            $table->bigInteger('legalsellcount')->nullable();
            $table->bigInteger('pl')->nullable();
            $table->bigInteger('pc')->nullable();
            $table->bigInteger('pf')->nullable();
            $table->bigInteger('py')->nullable();
            $table->bigInteger('pmax')->nullable();
            $table->bigInteger('pmin')->nullable();
            $table->bigInteger('tradevol')->nullable();
            $table->bigInteger('tradecash')->nullable();
            $table->bigInteger('BaseVol')->nullable();
            $table->bigInteger('EPS')->nullable();
            $table->bigInteger('minweek')->nullable();
            $table->bigInteger('maxweek')->nullable();
            $table->bigInteger('monthAVG')->nullable();
            $table->bigInteger('groupPE')->nullable();
            $table->bigInteger('sahamShenavar')->nullable();
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
