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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendorName')->unsigned();
            $table->foreign('vendorName')->references('id')->on('vendors')->onDelete('cascade');
            $table->string('certificateDate');
            $table->string('expireDate');
            $table->string('premiumCost');
            $table->string('certificateDoc');
            $table->string('inspectionPattern');
            $table->string('inspection1');
            $table->string('inspection2')->nullable();
            $table->string('inspection3')->nullable();
            $table->string('inspection4')->nullable();
            $table->string('inspection5')->nullable();
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
        Schema::dropIfExists('certificates');
    }
};
