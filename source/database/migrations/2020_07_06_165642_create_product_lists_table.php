<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('thumbnail', 30)->nullable();
            $table->unsignedInteger('brandId');
            $table->foreign('brandId')->references('id')->on('brands')->onDelete('cascade');
            $table->unsignedBigInteger('categoryId');
            $table->foreign('categoryId')->references('id')->on('categories')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('serialNo', 255);
            $table->string('shortDescription', 500)->nullable();
            $table->text('description')->nullable();
            $table->string('ram', 255)->nullable();
            $table->string('storage', 255)->nullable();
            $table->string('color', 255)->nullable();
            $table->string('modelName', 255)->nullable();
            $table->string('modelNo', 255)->nullable();
            $table->unsignedFloat('sellPrice', 8,2)->default(0)->nullable();
            $table->unsignedFloat('buyPrice', 8,2)->default(0)->nullable();
            $table->unsignedFloat('tax', 8,2)->default(0)->nullable();
            $table->unsignedFloat('discount', 8,2)->default(0)->nullable();
            $table->boolean('status')->default(1)->comment('0 => Inactive, 1 => Active' );
            $table->unsignedBigInteger('userId');
            $table->index(['categoryId']);
            $table->index(['brandId']);
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
        Schema::dropIfExists('medicine_lists');
    }
}
