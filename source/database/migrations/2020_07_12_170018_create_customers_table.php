<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
			$table->string('custCode');
			$table->string('name');
			$table->string('address')->nullable();
			$table->string('contact_person')->nullable();
			$table->string('contact_no_a')->nullable();
			$table->string('contact_no_b')->nullable();
			$table->string('email')->nullable();
			$table->string('website')->nullable();
			$table->integer('type');
			$table->string('trade_license')->nullable();
			$table->string('vat_no')->nullable();
			$table->string('gst_no')->nullable();
			$table->string('adhar_card_no')->nullable();
			$table->string('cst_no')->nullable();
			$table->string('pan_no')->nullable();
			$table->string('country')->nullable();
			$table->integer('state_id')->nullable();
			$table->integer('zone_id')->nullable();
			$table->string('bank_name')->nullable();
			$table->string('bank_acc_no')->nullable();
			$table->string('cr_limit')->nullable();
			$table->string('cr_limit_day')->nullable();
			$table->string('cr_margin')->nullable();
            $table->unsignedBigInteger('companyId')->nullable();
            $table->unsignedBigInteger('userId');
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
        Schema::dropIfExists('customers');
    }
}
