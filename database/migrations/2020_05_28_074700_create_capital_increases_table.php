<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCapitalIncreasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capital_increase_percents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('capital_increase_id')->nullable();
            $table->integer('percent');
            $table->string('type');
            $table->timestamps();
        });
       
        Schema::create('capital_increases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('namad_id');
            $table->enum('from',['assets','stored_gain','cash','compound']);
            $table->string('step')->nullable();
            $table->string('publish_date')->nullable();
            $table->string('link_to_codal')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('capital_increase_percents');
        Schema::dropIfExists('capital_increases');
    }
}
