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
        Schema::create('auditAssetList', function (Blueprint $table) {
            $table->id();
            $table->string('auditId')->nullable();
            $table->string('assetId')->nullable();
            $table->string('assetName')->nullable();
            $table->string('auditName')->nullable();
            $table->string('scheduledData')->nullable();
            $table->string('financialAssetId')->nullable();
            $table->string('department')->nullable();
            $table->string('section')->nullable();
            $table->string('assetType')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('assetModel')->nullable();
            $table->string('departmentId')->nullable();
            $table->string('sectionId')->nullable();
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
        Schema::dropIfExists('auditAssetList');

    }
};