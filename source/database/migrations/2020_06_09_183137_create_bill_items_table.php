<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_items', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('companyId');
            $table->unsignedBigInteger('bill_id');
            $table->foreign('bill_id')->references('id')->on('bills')->onDelete('cascade');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('batchId');
            $table->unsignedFloat('qty', 8,2)->default(0)->nullable();
            $table->unsignedFloat('free', 8,2)->default(0)->nullable();
            $table->unsignedFloat('price', 8,2)->default(0)->nullable();
            $table->unsignedFloat('basic', 8,2)->default(0)->nullable();
            $table->unsignedFloat('discount', 8,2)->default(0)->nullable();
            $table->unsignedFloat('tax', 8,2)->default(0)->nullable();
            $table->unsignedFloat('amount', 8,2)->default(0)->nullable();
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
        Schema::dropIfExists('bill_items');
    }
}
