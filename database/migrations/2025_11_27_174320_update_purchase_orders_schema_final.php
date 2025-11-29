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
        // Ensure purchase_orders table has all necessary columns
        if (Schema::hasTable('purchase_orders')) {
            Schema::table('purchase_orders', function (Blueprint $table) {
                // Add missing columns if they don't exist
                if (!Schema::hasColumn('purchase_orders', 'po_number')) {
                    $table->string('po_number')->unique()->after('id');
                }
                if (!Schema::hasColumn('purchase_orders', 'user_id')) {
                    $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->after('po_number');
                }
                if (!Schema::hasColumn('purchase_orders', 'supplier_id')) {
                    $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict')->after('user_id');
                }
                if (!Schema::hasColumn('purchase_orders', 'status')) {
                    $table->enum('status', ['draft', 'ordered', 'received', 'cancelled'])->default('draft')->after('supplier_id');
                }
                if (!Schema::hasColumn('purchase_orders', 'order_date')) {
                    $table->date('order_date')->nullable()->after('status');
                }
                if (!Schema::hasColumn('purchase_orders', 'expected_delivery_date')) {
                    $table->date('expected_delivery_date')->nullable()->after('order_date');
                }
                if (!Schema::hasColumn('purchase_orders', 'delivered_date')) {
                    $table->date('delivered_date')->nullable()->after('expected_delivery_date');
                }
                if (!Schema::hasColumn('purchase_orders', 'notes')) {
                    $table->text('notes')->nullable()->after('delivered_date');
                }
                if (!Schema::hasColumn('purchase_orders', 'total_amount')) {
                    $table->decimal('total_amount', 10, 2)->default(0)->after('notes');
                }
            });
        }

        // Ensure purchase_order_items table has all necessary columns
        if (Schema::hasTable('purchase_order_items')) {
            Schema::table('purchase_order_items', function (Blueprint $table) {
                // Add missing columns if they don't exist
                if (!Schema::hasColumn('purchase_order_items', 'inventory_id')) {
                    $table->foreignId('inventory_id')->constrained('inventories')->onDelete('cascade')->after('purchase_order_id');
                }
                if (!Schema::hasColumn('purchase_order_items', 'quantity')) {
                    $table->integer('quantity')->default(1)->after('inventory_id');
                }
                if (!Schema::hasColumn('purchase_order_items', 'unit_price')) {
                    $table->decimal('unit_price', 10, 2)->default(0)->after('quantity');
                }
                if (!Schema::hasColumn('purchase_order_items', 'total_price')) {
                    $table->decimal('total_price', 10, 2)->default(0)->after('unit_price');
                }
                if (!Schema::hasColumn('purchase_order_items', 'received_quantity')) {
                    $table->integer('received_quantity')->default(0)->after('total_price');
                }
                if (!Schema::hasColumn('purchase_order_items', 'received_at')) {
                    $table->timestamp('received_at')->nullable()->after('received_quantity');
                }
                if (!Schema::hasColumn('purchase_order_items', 'notes')) {
                    $table->text('notes')->nullable()->after('received_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This is a one-way migration to fix schema, so we don't implement down()
        // to avoid accidentally dropping columns that might be in use
    }
};
