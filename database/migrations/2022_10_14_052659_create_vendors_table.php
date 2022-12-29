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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendorName');
            $table->bigInteger('vendorType')->unsigned();
            $table->foreign('vendorType')->references('id')->on('vendor_types')->onDelete('cascade');
            $table->string('address');
            $table->string('email')->unique('vendors');
            $table->string('altEmail')->unique();
            $table->string('contactNo');
            $table->string('altContactNo');
            $table->string('contactPerson');
            $table->string('reMarks');
            $table->string('gstNo');
            $table->string('gstCertificate');
            $table->string('msmeCertificate');
            $table->string('canceledCheque');
            $table->timestamp('email_verified_at')->nullable();
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
        Schema::dropIfExists('vendors');
    }
};
