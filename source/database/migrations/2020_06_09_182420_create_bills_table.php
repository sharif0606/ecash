<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
			$table->integer('bill_no');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
			$table->string('type');
			$table->string('bill_term');
			$table->string('cus_gst')->nullable();
			$table->date('bill_date');
			$table->date('due_date')->nullable();
			$table->unsignedFloat('sub_total', 10, 2)->default(0);
			$table->unsignedFloat('total_tax', 10, 2)->default(0);
			$table->unsignedFloat('total_dis', 10, 2)->default(0);
			$table->unsignedFloat('total_due', 10, 2)->default(0);
			$table->unsignedFloat('total_amount', 10, 2)->default(0);
			$table->string('amount_in_word')->nullable();
			$table->string('trans_pincode')->nullable();
			$table->string('delivery_add')->nullable();
			$table->text('note')->nullable();
			$table->string('cheque_no')->nullable();
			$table->string('bank_name')->nullable();
			$table->date('cheque_date')->nullable();
			$table->string('cancel_reason',500)->nullable();
			$table->boolean('status')->default(1)->comment('0 => Inactive, 1 => Active' );
			$table->unsignedBigInteger('companyId');
            $table->unsignedBigInteger('userId');
            $table->index('customer_id');
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
        Schema::dropIfExists('bills');
    }
}
