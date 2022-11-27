<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('companyId')->nullable();
            $table->unsignedBigInteger('branchId');
            $table->foreign('branchId')->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('productId');
            $table->foreign('productId')->references('id')->on('products')->onDelete('cascade');
            $table->string('batchId')->nullable();
            $table->unsignedInteger('stock')->nullable()->default(0);
            $table->unsignedFloat('sellPrice', 8,2)->nullable()->default(0);
            $table->unsignedFloat('buyPrice', 8,2)->nullable()->default(0);
            $table->unsignedInteger('discount')->nullable()->default(0);
            $table->unsignedFloat('tax', 8,2)->nullable()->default(0);
            $table->date('expiryDate')->nullable();
            $table->date('manufDate')->nullable();
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
        Schema::dropIfExists('stocks');
    }
}
