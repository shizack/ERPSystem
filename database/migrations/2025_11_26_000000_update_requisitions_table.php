<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            // Add missing columns
            if (!Schema::hasColumn('requisitions', 'description')) {
                $table->text('description')->nullable()->after('quantity');
            }
            
            if (!Schema::hasColumn('requisitions', 'reason_for_rejection')) {
                $table->text('reason_for_rejection')->nullable()->after('status');
            }
            
            // Rename item_id to product_id if needed
            if (Schema::hasColumn('requisitions', 'item_id') && !Schema::hasColumn('requisitions', 'product_id')) {
                $table->renameColumn('item_id', 'product_id');
                
                // Update the foreign key constraint
                $table->dropForeign(['item_id']);
                $table->foreign('product_id')->references('item_id')->on('items');
            }
        });
    }

    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            if (Schema::hasColumn('requisitions', 'description')) {
                $table->dropColumn('description');
            }
            
            if (Schema::hasColumn('requisitions', 'reason_for_rejection')) {
                $table->dropColumn('reason_for_rejection');
            }
            
            if (Schema::hasColumn('requisitions', 'product_id') && !Schema::hasColumn('requisitions', 'item_id')) {
                $table->renameColumn('product_id', 'item_id');
                
                // Restore the foreign key constraint
                $table->dropForeign(['product_id']);
                $table->foreign('item_id')->references('item_id')->on('items');
            }
        });
    }
};
