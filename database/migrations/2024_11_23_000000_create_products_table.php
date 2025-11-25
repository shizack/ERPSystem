<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('product_id')->unique();
            $table->string('category');
            $table->decimal('buying_price', 10, 2);
            $table->integer('quantity');
            $table->string('unit');
            $table->date('expiry_date')->nullable();
            $table->integer('threshold_value')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}