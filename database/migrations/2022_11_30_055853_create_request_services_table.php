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
        Schema::create('request_services', function (Blueprint $table) {
            $table->id();
            $table->string('userName');
            $table->bigInteger('assetName')->unsigned();
            $table->foreign('assetName')->references('id')->on('assets')->onDelete('cascade');
            $table->string('amcStatus');
            $table->string('warrantyStatus');
            $table->string('insuranceStatus');
            $table->string('problem');
            $table->string('problemNote');
            $table->string('problemRemrak');
            $table->string('image1');
            $table->string('image2');
            $table->bigInteger('vendorName')->unsigned();
            $table->foreign('vendorName')->references('id')->on('vendors')->onDelete('cascade')->nullable();
            $table->string('vendorEmail')->nullable();
            $table->string('vendorAddress')->nullable();
            $table->string('vendorPhone')->nullable();
            $table->string('gstNo')->nullable();
            $table->string('dateOrDay')->nullable();
            $table->string('expectedDate')->nullable();
            $table->string('expectedDay')->nullable();
            $table->string('eWayBill')->nullable();
            $table->string('chargable')->nullable();
            $table->string('returnable')->nullable();
            $table->string('delivery')->nullable();
            $table->boolean('jobWork')->nullable();
            $table->boolean('repair')->nullable();
            $table->string('personName')->nullable();
            $table->string('serviceStatus')->nullable();
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
        Schema::dropIfExists('request_services');
    }
};
