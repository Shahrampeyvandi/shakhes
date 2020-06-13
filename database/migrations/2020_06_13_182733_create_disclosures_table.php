<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisclosuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disclosures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('namad_id');
            $table->string('subject');
            $table->string('link_to_codal')->nullable();
            $table->string('publish_date')->nullable();
            $table->enum('group',['a','b']);

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
        Schema::dropIfExists('disclosures');
    }
}
