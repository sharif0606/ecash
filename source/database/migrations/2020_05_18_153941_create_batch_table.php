<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('batchId', 255);
            $table->unsignedBigInteger('courseId')->nullable();
			$table->date('startDate')->nullable();
			$table->date('endDate')->nullable();
            $table->string('bslot', 255)->nullable();
            $table->string('btime', 255)->nullable();
            $table->unsignedBigInteger('trainerId', 255)->nullable();
			$table->date('examDate')->nullable();
			$table->time('examTime')->nullable();
			$table->unsignedBigInteger('examRoom')->nullable();
			$table->unsignedFloat('price', 10, 2)->default(0);
			$table->unsignedFloat('discount', 10, 2)->default(0);
            $table->boolean('status')->default(0);
            $table->unsignedBigInteger('userId');
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
        Schema::dropIfExists('batches');
    }
}
