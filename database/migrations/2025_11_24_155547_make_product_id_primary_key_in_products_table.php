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
        // Drop the existing primary key if it's on the 'id' column
        $table->dropPrimary('products_id_primary');
        // Make product_id the primary key
        $table->primary('product_id');
        // Optionally, drop the id column if you don't need it
        $table->dropColumn('id');
    });
    }

    public function down()
    {
    Schema::table('products', function (Blueprint $table) {
        $table->dropPrimary('products_product_id_primary');
        $table->id();
    });
}
};
