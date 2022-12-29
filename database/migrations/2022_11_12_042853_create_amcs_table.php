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
        Schema::create('amcs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendorName')->unsigned();
            $table->foreign('vendorName')->references('id')->on('vendors')->onDelete('cascade');
            $table->string('periodFrom');
            $table->string('periodTo');
            $table->string('premiumCost');
            $table->string('amcDoc');
            $table->string('servicePattern');
            $table->string('service1');
            $table->string('service2')->nullable();
            $table->string('service3')->nullable();
            $table->string('service4')->nullable();
            $table->string('service5')->nullable();
            $table->bigInteger('department')->unsigned();
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
            $table->bigInteger('section')->unsigned();
            $table->foreign('section')->references('id')->on('sections')->onDelete('cascade');
            $table->bigInteger('assetType')->unsigned();
            $table->foreign('assetType')->references('id')->on('assetTypes')->onDelete('cascade');
            $table->bigInteger('assetName')->unsigned();
            $table->foreign('assetName')->references('id')->on('assets')->onDelete('cascade');
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
        Schema::dropIfExists('amcs');
    }
};
