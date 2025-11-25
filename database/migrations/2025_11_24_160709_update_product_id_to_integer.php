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
    // First, drop the foreign key constraint
    \DB::statement('ALTER TABLE usage_logs DROP CONSTRAINT IF EXISTS usage_logs_product_id_foreign');
    
    // Then update the data types
    \DB::statement('ALTER TABLE products ALTER COLUMN product_id TYPE INTEGER USING (product_id::integer)');
    \DB::statement('ALTER TABLE usage_logs ALTER COLUMN product_id TYPE INTEGER USING (product_id::integer)');
    
    // Recreate the foreign key constraint
    \DB::statement('
        ALTER TABLE usage_logs 
        ADD CONSTRAINT usage_logs_product_id_foreign 
        FOREIGN KEY (product_id) 
        REFERENCES products(product_id) 
        ON DELETE CASCADE
    ');
}

public function down()
{
    // Drop the foreign key
    \DB::statement('ALTER TABLE usage_logs DROP CONSTRAINT IF EXISTS usage_logs_product_id_foreign');
    
    // Revert column types
    \DB::statement('ALTER TABLE usage_logs ALTER COLUMN product_id TYPE VARCHAR');
    \DB::statement('ALTER TABLE products ALTER COLUMN product_id TYPE VARCHAR');
    
    // Recreate the original foreign key
    \DB::statement('
        ALTER TABLE usage_logs 
        ADD CONSTRAINT usage_logs_product_id_foreign 
        FOREIGN KEY (product_id) 
        REFERENCES products(product_id) 
        ON DELETE CASCADE
    ');
}
};
