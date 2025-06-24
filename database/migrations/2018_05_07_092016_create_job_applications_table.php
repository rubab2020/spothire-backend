<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('applicant_id')->unsigned()->index();
            $table->foreign('applicant_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('job_id')->unsigned()->index();
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');

            $table->string('application_status')->default(null);
            $table->date('interview_date')->default(null);
            $table->time('interview_time')->default(null);
            $table->boolean('is_short_listed')->default(0);
            $table->integer('rating_status')->default(null);
            $table->integer('rating')->default(null);
            // $table->datetime('expiary_date_if_not_assigned')->nullable();
            // $table->datetime('preserve_discontinue_till')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('job_applications');
    }
}
