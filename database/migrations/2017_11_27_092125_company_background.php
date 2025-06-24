<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyBackground extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_backgrounds', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->string('industry')->nullable();
            $table->string('location')->nullable();
            $table->text('location_data')->nullable();
            $table->date('inception_date')->nullable();
            $table->longText('is_enrolled')->nullable();
            $table->string('annual_turnover')->nullable();
            $table->integer('total_employees')->unsigned()->nullable();
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
        Schema::dropIfExists('company_backgrounds');
    }
}
