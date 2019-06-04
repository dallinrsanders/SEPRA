<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Vulnfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vulnfiles', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('vulnerability_id');
			$table->foreign('vulnerability_id')->references('id')->on('vulnerabilities')->onDelete('cascade');
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
        Schema::dropIfExists('vulnfiles');
    }
}
