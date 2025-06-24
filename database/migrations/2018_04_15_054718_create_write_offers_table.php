<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWriteOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('looking_for');
            $table->string('slug');
            $table->string('employment_type');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('salary_from')->nullable();
            $table->string('salary_to')->nullable();
            $table->string('salary_type')->nullable();
            $table->string('is_salary_negotiable')->nullable();
            $table->string('address')->nullable();
            $table->string('location');
            $table->string('department');
            $table->string('min_experience');
            $table->string('min_qualification');
            $table->longText('description');
            $table->longText('requirements')->nullable();
            $table->integer('advert_duration');
            $table->date('advert_deadline');
            $table->string('currency_type')->default('BDT');
            $table->boolean('is_payment_completed')->default(0);
            $table->boolean('review_status')->default('p'); // p = pending, a = approved, r = rejected     
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
        Schema::dropIfExists('jobs');
    }
}
