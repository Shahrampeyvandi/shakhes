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
            $table->string('market');
            $table->string('flow');
            $table->string('mahemali')->nullable();
            $table->timestamps();
        });
        DB::table('namads')->insert(
            array(
                'symbol'=>'ذوب',
                'name'=>'ذوب',
                'market'=>'بورس',
                'flow'=>'1',
                'mahemali'=>'1',
                )
        );
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
