<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Mquestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mquestions', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('msection_id');
			$table->foreign('msection_id')->references('id')->on('msections')->onDelete('cascade');
			$table->string("question");
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
        Schema::dropIfExists('mquestions');
    }
}
