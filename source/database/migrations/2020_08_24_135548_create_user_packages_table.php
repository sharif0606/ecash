<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_packages', function (Blueprint $table) {
            $table->id();
			$table->integer('companyId')->nullable();
			$table->integer('packageId')->nullable();
			$table->unsignedFloat('price', 10, 2)->default(0)->nullable();
			$table->unsignedFloat('discount', 10, 2)->default(0)->nullable();
            $table->string('couponCode')->nullable();
			$table->date('startAt')->nullable();
			$table->date('endAt')->nullable();
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
        Schema::dropIfExists('user_packages');
    }
}
