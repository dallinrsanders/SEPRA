<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Hostfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hostfiles', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('host_id');
			$table->foreign('host_id')->references('id')->on('hosts')->onDelete('cascade');
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
        Schema::dropIfExists('hostfiles');
    }
}
