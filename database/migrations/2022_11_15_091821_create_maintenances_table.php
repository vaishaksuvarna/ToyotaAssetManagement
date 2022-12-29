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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('department')->unsigned();
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
            $table->bigInteger('section')->unsigned();
            $table->foreign('section')->references('id')->on('sections')->onDelete('cascade');
            $table->bigInteger('assetType')->unsigned();
            $table->foreign('assetType')->references('id')->on('assetTypes')->onDelete('cascade');
            $table->bigInteger('assetName')->unsigned()->unique();
            $table->foreign('assetName')->references('id')->on('assets')->onDelete('cascade');
            $table->string('amcStatus');
            $table->string('warrantyStatus');
            $table->string('warrantyType');
            $table->string('maintenanceId');
            $table->string('maintenanceType');
            $table->string('severity');
            $table->string('problemNote');
            $table->string('bpImages1');
            $table->string('bpImages2')->nullable();;
            $table->string('bpImages3')->nullable();;
            $table->string('bpImages4')->nullable();;
            $table->string('partsOrConsumable');
            $table->string('affectedMachine');
            $table->string('affectedManHours');
            $table->string('shutdownOrUtilization')->nullable();
            $table->string('machineDetails')->nullable();
            $table->string('offOrUtilization')->nullable();
            $table->string('manHoursDetails')->nullable();
            $table->string('dateFrom');
            $table->string('dateTo');
            $table->string('timeFrom');
            $table->string('timeTo');
            $table->string('action')->nullable();
            $table->string('closedMaintenance')->nullable();
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
        Schema::dropIfExists('maintenances');
    }
};
