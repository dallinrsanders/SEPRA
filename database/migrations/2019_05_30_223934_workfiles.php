<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Workfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workfiles', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('workspace_id');
			$table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
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
        Schema::dropIfExists('workfiles');
    }
}
