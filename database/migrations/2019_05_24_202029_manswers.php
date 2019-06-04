<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Manswers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manswers', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('mquestion_id');
			$table->foreign('mquestion_id')->references('id')->on('mquestions')->onDelete('cascade');
			$table->unsignedBigInteger('workspacemethodology_id');
			//$table->foreign('workspacemethodology_id')->references('id')->on('workspacemethodologies')->onDelete('cascade');
			$table->text('answer');
			$table->integer('completed');
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
        Schema::dropIfExists('manswers');
    }
}
