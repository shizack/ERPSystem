<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    // First, drop the foreign key constraint on usage_logs if it exists
    if (Schema::hasTable('usage_logs') && Schema::hasColumn('usage_logs', 'product_id')) {
        try {
            Schema::table('usage_logs', function (Blueprint $table) {
                $table->dropForeign(['product_id']);
            });
        } catch (\Exception $e) {
            // Constraint might not exist, which is fine
        }
    }

    // Add the new columns if they don't exist
    Schema::table('products', function (Blueprint $table) {
        if (!Schema::hasColumn('products', 'supplier_id')) {
            $table->foreignId('supplier_id')->nullable()->after('id');
        }
        
        if (!Schema::hasColumn('products', 'category_id')) {
            $table->foreignId('category_id')->nullable()->after('supplier_id');
        }
    });

    // Re-add the foreign key constraint with CASCADE if it doesn't exist
    if (Schema::hasTable('usage_logs') && Schema::hasColumn('usage_logs', 'product_id')) {
        try {
            Schema::table('usage_logs', function (Blueprint $table) {
                $table->foreign('product_id')
                      ->references('product_id')
                      ->on('products')
                      ->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // Constraint might already exist, which is fine
        }
    }

    // Add foreign key constraints for supplier and category if they don't exist
    Schema::table('products', function (Blueprint $table) {
        // Check if the foreign key constraint exists
        $constraints = \DB::select("
            SELECT conname
            FROM pg_constraint 
            WHERE conname = 'products_supplier_id_foreign'
        ");

        if (empty($constraints)) {
            $table->foreign('supplier_id')
                  ->references('id')
                  ->on('suppliers')
                  ->onDelete('set null');
        }
        
        $constraints = \DB::select("
            SELECT conname
            FROM pg_constraint 
            WHERE conname = 'products_category_id_foreign'
        ");

        if (empty($constraints)) {
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');
        }
    });
}

public function down()
{
    // Drop foreign key constraints if they exist
    Schema::table('products', function (Blueprint $table) {
        // Drop supplier_id foreign key
        try {
            $table->dropForeign(['supplier_id']);
        } catch (\Exception $e) {
            // Ignore if constraint doesn't exist
        }
        
        // Drop category_id foreign key
        try {
            $table->dropForeign(['category_id']);
        } catch (\Exception $e) {
            // Ignore if constraint doesn't exist
        }
    });

    // Drop the columns if they exist
    Schema::table('products', function (Blueprint $table) {
        if (Schema::hasColumn('products', 'supplier_id')) {
            $table->dropColumn('supplier_id');
        }
        
        if (Schema::hasColumn('products', 'category_id')) {
            $table->dropColumn('category_id');
        }
    });

    // Note: We don't drop the suppliers and categories tables here
    // as they might be used by other parts of the application
}
};