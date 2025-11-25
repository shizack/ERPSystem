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
        // This table definition is required for the 'requisitions' foreign key to work.
        Schema::create('items', function (Blueprint $table) {
            $table->id('item_id'); // Creates the primary key referenced by requisitions
            $table->string('item_code', 50)->unique();
            $table->string('item_name', 150);
            $table->text('description')->nullable();
            
            // Note: Using a standard string and adding a comment for PostgreSQL CHECK constraint equivalent
            $table->string('department', 50)->comment('CHECK (department IN (\'cook\', \'room_management\', \'gardening\', \'frontdesk\', \'maintenance\'))');
            
            $table->string('unit', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};