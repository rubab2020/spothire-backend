<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserConcentrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_concentrations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('qualification_id')->unsigned();;
            $table->foreign('qualification_id')->references('id')->on('qualification')->onDelete('cascade');
            $table->string('concentration_name')->default(null);
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
        Schema::dropIfExists('user_concentrations');
    }
}
