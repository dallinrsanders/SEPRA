<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class VulnerabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vulnerabilities', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->unsignedBigInteger('service_id');
			$table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
			$table->integer('classification');
			$table->integer('easeofresolution');
			$table->string('name');
			$table->text('description');
			$table->string('reference');
			$table->string('cve');
			$table->text('resolution');
			$table->string('policyviolation');
			$table->integer('accountability');
			$table->integer('availability');
			$table->integer('confidentiality');
			$table->integer('integrity');
			$table->text('data');
			$table->text('request');
			$table->text('response');
			$table->string('method');
			$table->string('paramname');
			$table->string('params');
			$table->string('path');
			$table->string('statuscode');
			$table->string('query');
			$table->string('website');
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
        Schema::dropIfExists('vulnerabilities');
    }
}
