<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration safely removes unused/duplicate tables:
     * - orders, order_items (sales system - not used)
     * - guest_orders, guests (guest management - not implemented)
     * - rooms (hotel feature - not used)
     * - inventory (using products table instead)
     * - items (using products table instead)
     * - new_purchase_orders, new_purchase_order_items (migration artifacts)
     * - histories (using usage_logs and stock_movements)
     */
    public function up(): void
    {
        // Drop foreign keys first to avoid constraint errors
        if (Schema::hasTable('new_purchase_order_items')) {
            Schema::table('new_purchase_order_items', function (Blueprint $table) {
                try {
                    $table->dropForeign(['inventory_id']);
                } catch (\Throwable $e) {
                    // Ignore if FK doesn't exist
                }
            });
        }
        
        // Drop sales/orders tables (removed from system)
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        
        // Drop guest management tables (not implemented)
        Schema::dropIfExists('guest_orders');
        Schema::dropIfExists('guests');
        
        // Drop unused hotel/room feature
        Schema::dropIfExists('rooms');
        
        // Drop migration artifact tables (before inventory/items they depend on)
        Schema::dropIfExists('new_purchase_order_items');
        Schema::dropIfExists('new_purchase_orders');
        
        // Drop duplicate/legacy inventory tables
        Schema::dropIfExists('inventory');
        Schema::dropIfExists('items');
        
        // Drop histories table (using usage_logs instead)
        Schema::dropIfExists('histories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reversing this migration would require recreating all table structures
        // Since these tables are unused, we don't implement down() to avoid complexity
        // If you need to restore, refer to the original migration files
    }
};
