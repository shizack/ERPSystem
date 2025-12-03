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
        // For Postgres with dependent FKs, drop FKs first, then alter, then recreate FKs.
        // Drop foreign keys referencing products(product_id)
        Schema::table('usage_logs', function (Blueprint $table) {
            try { $table->dropForeign(['product_id']); } catch (\Throwable $e) {}
        });
        Schema::table('order_items', function (Blueprint $table) {
            try { $table->dropForeign(['product_id']); } catch (\Throwable $e) {}
        });
        Schema::table('requisitions', function (Blueprint $table) {
            try { $table->dropForeign(['product_id']); } catch (\Throwable $e) {}
        });
        Schema::table('purchase_order_items', function (Blueprint $table) {
            try { $table->dropForeign(['product_id']); } catch (\Throwable $e) {}
        });
        Schema::table('supplier_product', function (Blueprint $table) {
            try { $table->dropForeign(['product_id']); } catch (\Throwable $e) {}
        });

        // Drop unique and alter column type
        Schema::table('products', function (Blueprint $table) {
            try { $table->dropUnique('products_product_id_unique'); } catch (\Throwable $e) {}
            $table->integer('product_id')->unique()->change();
        });

        // Recreate foreign keys
        Schema::table('usage_logs', function (Blueprint $table) {
            try { $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade'); } catch (\Throwable $e) {}
        });
        Schema::table('order_items', function (Blueprint $table) {
            try { $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade'); } catch (\Throwable $e) {}
        });
        Schema::table('requisitions', function (Blueprint $table) {
            try { $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade'); } catch (\Throwable $e) {}
        });
        Schema::table('purchase_order_items', function (Blueprint $table) {
            try { $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade'); } catch (\Throwable $e) {}
        });
        Schema::table('supplier_product', function (Blueprint $table) {
            try { $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade'); } catch (\Throwable $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Revert back to string
            $table->string('product_id')->unique()->change();
        });
    }
};
