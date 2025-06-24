<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Registration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique()->nullable();

            $table->string('password');

            $table->longText('about')->nullable();
            $table->string('picture')->nullable();
            $table->string('picture_sm')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('cover_photo_sm')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();

            $table->string('company_expertise')->nullable();

            $table->string('user_type');
            
            $table->string('provider')->nullable();
            $table->string('provider_id')->unique()->nullable();
            
            $table->string('remember_token');

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
