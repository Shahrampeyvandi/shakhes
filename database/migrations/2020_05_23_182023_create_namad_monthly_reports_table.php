<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNamadMonthlyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('namad_monthly_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('namad_id');
            $table->integer('value');
            $table->string('month')->nullable();
            $table->string('year')->nullable();
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
        Schema::dropIfExists('namad_monthly_reports');
    }
}
