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
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('department')->unsigned();
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
            $table->bigInteger('selectSection')->unsigned();
            $table->foreign('selectSection')->references('id')->on('sections')->onDelete('cascade');
            $table->bigInteger('assetType')->unsigned();
            $table->foreign('assetType')->references('id')->on('assettypes')->onDelete('cascade');
            $table->string('selectAssetType');
            $table->bigInteger('selectAsset')->unsigned()->nullable();
            $table->foreign('selectAsset')->references('id')->on('assets')->onDelete('cascade');
            $table->string('selectAssetId')->nullable();
            $table->string('code');
            $table->string('codeGenerator');
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
        Schema::dropIfExists('labels');
    }
};
