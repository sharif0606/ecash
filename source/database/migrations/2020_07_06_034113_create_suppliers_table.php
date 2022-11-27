<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supCode');
            $table->string('name');
            $table->longText('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_no_b', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->integer('type')->nullable();
            $table->string('trade_license')->nullable();
            $table->string('vat_no')->nullable();
            $table->string('country')->nullable();
            $table->string('state_id')->nullable();
            $table->string('zone_id')->nullable();	     	  	
            $table->string('bank_name')->nullable();
            $table->string('bank_acc_no')->nullable();
            $table->unsignedFloat('cr_limit', 10, 2)->default(0)->nullable();
            $table->string('cr_limit_day')->nullable();
            $table->unsignedFloat('cr_margin', 10, 2)->default(0)->nullable();
            $table->integer('bkash_type')->nullable();
            $table->string('bkash_acc_no')->nullable();
            $table->integer('rocket_type')->nullable();
            $table->string('rocket_acc_no')->nullable();
            $table->integer('companyId');
            $table->integer('userId');
            $table->boolean('status')->default(1)->comment('0 => Inactive, 1 => Active' );
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
        Schema::dropIfExists('suppliers');
    }
}
