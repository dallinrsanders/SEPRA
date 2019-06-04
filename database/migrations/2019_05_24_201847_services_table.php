<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('host_id');
			$table->foreign('host_id')->references('id')->on('hosts')->onDelete('cascade');
			$table->string('name');
			$table->string('port');
			$table->string('protocol');
			$table->string('version');
			$table->string('status');
			$table->integer('website');
			$table->text('description');
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
        Schema::dropIfExists('services');
    }
}
