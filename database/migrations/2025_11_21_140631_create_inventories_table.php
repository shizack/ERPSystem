<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id('item_id');
            $table->string('item_name', 255);
            $table->decimal('stock_level', 10, 2)->default(0);
            $table->string('unit_of_measure', 50)->nullable();
            $table->decimal('reorder_point', 10, 2)->default(0);
            $table->string('location', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};