<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Msections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msections', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('methodology_id');
			$table->foreign('methodology_id')->references('id')->on('methodologies')->onDelete('cascade');
			$table->string('title');
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
        Schema::dropIfExists('msections');
    }
}
