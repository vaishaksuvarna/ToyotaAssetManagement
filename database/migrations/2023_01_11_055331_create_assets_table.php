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
            $table->id();
            $table->string('assetId');
            $table->string('assetNo');
            $table->string('projectName');
            $table->bigInteger('requesterDepartment')->unsigned();
            $table->foreign('requesterDepartment')->references('id')->on('requester_departments')->onDelete('cascade');
            $table->bigInteger('unitPlant')->unsigned();
            $table->foreign('unitPlant')->references('id')->on('units')->onDelete('cascade');
            $table->bigInteger('line')->unsigned();
            $table->foreign('line')->references('id')->on('lines')->onDelete('cascade');
            $table->string('component');
            $table->string('operationNo');
            $table->string('assetName');
            $table->string('operationName');
            $table->string('equipmentType');
            $table->string('dateOfRequest');
            $table->string('requesterName');
            $table->string('yearOfMfg');
            $table->string('countryOfMfg');
            $table->string('yearOfInstallTKAP');
            $table->string('usedOrNew');
            $table->string('usagecode');
            $table->string('assetWeight');
            // $table->bigInteger('controlDepartment')->unsigned();
            // $table->foreign('controlDepartment')->references('id')->on('control_departments')->onDelete('cascade');
            // $table->bigInteger('userDepartment')->unsigned();
            // $table->foreign('userDepartment')->references('id')->on('user_departments')->onDelete('cascade');
            $table->string('controlDepartment');
            $table->string('userDepartment');
            $table->string('section');
            $table->string('assetImage');
            $table->string('mfgSlNo');
            $table->string('status');
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
        Schema::dropIfExists('assets');
    }
};
