<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Mfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mfiles', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('manswer_id');
			$table->foreign('manswer_id')->references('id')->on('manswers')->onDelete('cascade');
			$table->unsignedBigInteger('upload_id');
			$table->foreign('upload_id')->references('id')->on('uploads')->onDelete('cascade');
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
        Schema::dropIfExists('mfiles');
    }
}
