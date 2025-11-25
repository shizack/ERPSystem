<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id('req_id');
            
            // Correct way to define foreign keys to non-standard primary keys
            $table->foreignId('requested_by')->constrained('employees', 'employee_id'); 
            
            // THIS MUST REFERENCE THE 'items' table (created in the previous step)
            // It assumes the 'items' table exists and has an 'item_id' column
            $table->foreignId('item_id')->constrained('items', 'item_id'); 
            
            $table->decimal('quantity', 10, 2);
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('admin_id')->on('admins');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};