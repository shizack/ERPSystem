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
        if (! Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                // Minimal base columns; earlier migration already defines full schema if table exists
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop if this migration actually created it
        if (Schema::hasTable('suppliers') && !Schema::hasColumn('suppliers', 'name')) {
            Schema::dropIfExists('suppliers');
        }
    }
};
