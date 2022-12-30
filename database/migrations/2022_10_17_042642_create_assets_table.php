<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id('id');
            $table->string('assetId');
            $table->bigInteger('department')->unsigned();
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
            $table->bigInteger('section')->unsigned();
            $table->foreign('section')->references('id')->on('sections')->onDelete('cascade');
            $table->string('assetName');
            $table->string('financialAssetId');
            $table->bigInteger('assetType')->unsigned();
            $table->foreign('assetType')->references('id')->on('assettypes')->onDelete('cascade');
            $table->bigInteger('unit')->unsigned();
            $table->foreign('unit')->references('id')->on('units')->onDelete('cascade');
            $table->bigInteger('project')->unsigned();
            $table->foreign('project')->references('id')->on('projects')->onDelete('cascade');
            $table->bigInteger('line')->unsigned();
            $table->foreign('line')->references('id')->on('lines')->onDelete('cascade');
            $table->string('operationNo');
            $table->string('usagecode');
            $table->string('yearOfMfg');
            $table->string('usedOrNew');
            $table->string('requesterName');
            $table->string('manufacturer');
            $table->string('description');
            $table->string('assetImage');
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
        Schema::dropIfExists('asset');
    }
};
