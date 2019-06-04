<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hosts', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('workspace_id');
			$table->foreign('workspace_id')->references('id')->on('workspaces')->onDelete('cascade');
			$table->string('name');
			$table->string('ip');
			$table->string('mac');
			$table->string('OS');
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
        Schema::dropIfExists('hosts');
    }
}
