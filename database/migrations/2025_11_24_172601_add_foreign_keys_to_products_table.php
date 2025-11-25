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
    Schema::table('products', function (Blueprint $table) {
        // Make sure the columns exist first
        if (!Schema::hasColumn('products', 'supplier_id')) {
            $table->foreignId('supplier_id')->nullable()->after('id')
                ->constrained()->onDelete('set null');
        }
        
        if (!Schema::hasColumn('products', 'category_id')) {
            $table->foreignId('category_id')->nullable()->after('supplier_id')
                ->constrained()->onDelete('set null');
        }
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropForeign(['supplier_id']);
        $table->dropForeign(['category_id']);
        $table->dropColumn(['supplier_id', 'category_id']);
    });
}
};
