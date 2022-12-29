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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('userType');
            $table->string('employee_id')->unique();
            $table->string('employee_name');
            $table->bigInteger('department')->unsigned();
            $table->foreign('department')->references('id')->on('departments')->onDelete('cascade');
            $table->string('designation');
            $table->string('mobile_number');
            $table->string('email')->unique();
            $table->string('user_name');
            $table->string('password');
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
        Schema::dropIfExists('users');
    }
};
