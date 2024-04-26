<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->string('api_token')->nullable();
            $table->integer('active')->default(false);
            $table->string('verification_code')->nullable();
            $table->string('password');
//            $table->string('national_id')->nullable();
//            $table->text('full_name')->nullable();
//            $table->string('job_id')->nullable();
//            $table->string('sector')->nullable();
//            $table->enum('type' , ['user' , 'customer'])->default('user');
            $table->rememberToken();
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
}
