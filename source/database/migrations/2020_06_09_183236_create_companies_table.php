<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
			$table->string('company_name')->nullable();
			$table->string('company_slogan')->nullable();
			$table->string('contact_number')->nullable();
			$table->string('company_email')->nullable();
			$table->string('company_add_a')->nullable();
			$table->string('company_add_b')->nullable();
            $table->string('trade_l', 50)->nullable();
            $table->string('tin')->nullable();
            $table->integer('verified')->default(0)->comment('0 => Not Requested, 1 => Requested, 2=> Verified' );
			$table->string('webiste')->nullable();
			$table->string('facebook')->nullable();
			$table->string('twitter')->nullable();
			$table->string('company_logo')->nullable();
			$table->string('billing_seal')->nullable();
			$table->string('billing_signature')->nullable();
			$table->text('billing_terms')->nullable();
			$table->string('currency')->nullable();
			$table->string('currency_symble')->nullable();
            $table->string('invoice')->nullable();
			$table->unsignedFloat('tax', 8,2)->default(0);
			$table->unsignedBigInteger('companyId');
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
        Schema::dropIfExists('companies');
    }
}
