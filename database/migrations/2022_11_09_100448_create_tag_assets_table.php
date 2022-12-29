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
        Schema::create('tag_assets', function (Blueprint $table) {
            $table->id();
            $table->string('tagAssetType');
            $table->bigInteger('assetId')->unsigned();
            $table->foreign('assetId')->references('id')->on('assets')->onDelete('cascade');
            $table->bigInteger('department')->unsigned();
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
            $table->bigInteger('section')->unsigned();
            $table->foreign('section')->references('id')->on('sections')->onDelete('cascade');
            $table->bigInteger('assetType')->unsigned();
            $table->foreign('assetType')->references('id')->on('assettypes')->onDelete('cascade');
            $table->bigInteger('assetName')->unsigned();
            $table->foreign('assetName')->references('id')->on('assets')->onDelete('cascade');
            $table->string('scanRfidNo')->nullable();
            $table->string('rfidNo');
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
        Schema::dropIfExists('tag_assets');
    }
};
