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
            $table->bigInteger('vendorName')->unsigned();
            $table->foreign('vendorName')->references('id')->on('vendors')->onDelete('cascade');
            $table->string('phoneNumber');
            $table->string('email');
            $table->string('vendorAddress');
            $table->bigInteger('assetType')->unsigned();
            $table->foreign('assetType')->references('id')->on('assettypes')->onDelete('cascade');
            $table->string('manufacturer');
            $table->string('assetModel');
            $table->string('poNo');
            $table->string('invoiceNo');
            $table->string('typeWarranty');
            $table->string('warrantyStartDate')->nullable();
            $table->string('warrantyEndDate')->nullable();
            $table->string('warrantyDocument')->nullable();
            $table->string('uploadDocument');
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
