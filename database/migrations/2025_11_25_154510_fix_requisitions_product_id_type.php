<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the existing foreign key constraint if it exists
        DB::statement('
            ALTER TABLE requisitions
            DROP CONSTRAINT IF EXISTS requisitions_product_id_foreign
        ');

        // Change the column type to match the products table (integer)
        DB::statement('
            ALTER TABLE requisitions 
            ALTER COLUMN product_id TYPE INTEGER 
            USING (product_id::integer)
        ');

        // Add the foreign key constraint
        DB::statement('
            ALTER TABLE requisitions
            ADD CONSTRAINT requisitions_product_id_foreign
            FOREIGN KEY (product_id) 
            REFERENCES products (product_id)
            ON DELETE RESTRICT
        ');
    }

    public function down(): void
    {
        // Drop the foreign key constraint
        DB::statement('
            ALTER TABLE requisitions
            DROP CONSTRAINT IF EXISTS requisitions_product_id_foreign
        ');

        // Change the column type back to bigint
        DB::statement('
            ALTER TABLE requisitions 
            ALTER COLUMN product_id TYPE BIGINT
        ');
    }
};