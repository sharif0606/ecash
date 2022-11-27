<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sup_id');
            $table->foreign('sup_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->integer('purchase_no');
            $table->string('type');
            $table->string('purchase_term');
			$table->string('sup_gst')->nullable();
            $table->date('purchase_date');
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
            $table->index('sup_id');
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
        Schema::dropIfExists('purchases');
    }
}
