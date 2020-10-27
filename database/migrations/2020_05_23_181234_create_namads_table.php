<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateNamadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('namads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('symbol');
            $table->string('name');
            $table->string('flow');
            $table->string('mahemali')->nullable();
            $table->boolean('is_holding')->default(0);
            $table->string('inscode')->nullable();
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
        Schema::dropIfExists('namads');
    }
}
