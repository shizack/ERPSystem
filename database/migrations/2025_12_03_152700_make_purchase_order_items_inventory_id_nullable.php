<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('purchase_order_items')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                // Drop FK that points to non-existent 'inventories' table
                try {
                    $table->dropForeign(['inventory_id']);
                } catch (\Throwable $e) {
                    // ignore if not exists
                }
            });
            Schema::table('purchase_order_items', function (Blueprint $table) {
                $table->unsignedBigInteger('inventory_id')->nullable()->change();
            });
            // If you later want FK, ensure it references correct table name 'inventory'
            // Schema::table('purchase_order_items', function (Blueprint $table) {
            //     $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('cascade');
            // });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('purchase_order_items')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                $table->unsignedBigInteger('inventory_id')->nullable(false)->change();
            });
        }
    }
};
