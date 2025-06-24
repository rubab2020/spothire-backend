<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesForFeeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks_images', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('feedback_id')->unsigned()->index();
            $table->foreign('feedback_id')->references('id')->on('feedbacks')->onDelete('cascade');

            $table->string('image');

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
        Schema::dropIfExists('feedbacks_images');
    }
}
