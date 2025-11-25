<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('usage_logs', function (Blueprint $table) {
        $table->id();
        $table->string('product_id'); // Changed from foreignId to string
        $table->integer('quantity_change');
        $table->string('reason');
        $table->text('notes')->nullable();
        $table->foreignId('recorded_by')->constrained('employees', 'employee_id');
        $table->timestamps();
        
        // Add the foreign key constraint
        $table->foreign('product_id')
              ->references('product_id')
              ->on('products')
              ->onDelete('cascade');
        
        $table->index(['product_id', 'created_at']);
    });
}

public function down()
{
    Schema::table('usage_logs', function (Blueprint $table) {
        $table->dropForeign(['product_id']);
    });
    Schema::dropIfExists('usage_logs');
}
};
