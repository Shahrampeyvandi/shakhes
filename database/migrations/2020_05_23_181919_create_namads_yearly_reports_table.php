<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNamadsYearlyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('namads_yearly_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('namad_id');
            $table->integer('profit');
            $table->integer('loss');
            $table->string('year');
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
        Schema::dropIfExists('namads_yearly_reports');
    }
}
