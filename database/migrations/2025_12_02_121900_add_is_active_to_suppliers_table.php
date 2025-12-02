<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                if (! Schema::hasColumn('suppliers', 'tax_identification_number')) {
                    $table->string('tax_identification_number')->nullable()->after('address');
                }
                if (! Schema::hasColumn('suppliers', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('tax_identification_number');
                }
                if (! Schema::hasColumn('suppliers', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('suppliers')) {
            Schema::table('suppliers', function (Blueprint $table) {
                if (Schema::hasColumn('suppliers', 'is_active')) {
                    $table->dropColumn('is_active');
                }
                if (Schema::hasColumn('suppliers', 'tax_identification_number')) {
                    $table->dropColumn('tax_identification_number');
                }
                if (Schema::hasColumn('suppliers', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};
