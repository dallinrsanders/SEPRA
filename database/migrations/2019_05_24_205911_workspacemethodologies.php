<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Workspacemethodologies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workspacemethodologies', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('workspace_id');
			$table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
			$table->unsignedBigInteger('methodology_id');
			$table->foreign('methodology_id')->references('id')->on('methodologies')->onDelete('cascade');
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
        Schema::dropIfExists('workspacemethodologies');
    }
}
