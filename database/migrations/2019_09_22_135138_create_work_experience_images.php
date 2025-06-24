<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkExperienceImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_experience_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('image');
            $table->string('image_sm');
            $table->string('description')->nullable();

            $table->integer('work_id')->unsigned()->index();
            $table->foreign('work_id')->references('id')->on('work_experience')->onDelete('cascade');
            
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
        Schema::dropIfExists('work_experience_images');
    }
}
