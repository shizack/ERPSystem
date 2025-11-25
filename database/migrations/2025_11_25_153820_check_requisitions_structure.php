<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // This will log the table structure to the Laravel log
        $columns = DB::select("
            SELECT column_name, data_type, is_nullable, column_default
            FROM information_schema.columns
            WHERE table_name = 'requisitions'
            ORDER BY ordinal_position
        ");

        \Log::info('Requisitions table structure:', (array) $columns);
    }

    public function down()
    {
        // Nothing to do here
    }
};