<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Find requisitions that have usage_logs (indicating they were approved)
        // but still have pending status, and fix them
        $requisitionsWithUsageLogs = DB::table('usage_logs')
            ->whereRaw("reason LIKE 'Requisition %approved'")
            ->select(DB::raw("CAST(SUBSTRING(reason FROM 'Requisition #([0-9]+)') AS INTEGER) as req_id"))
            ->distinct()
            ->pluck('req_id');
        
        if ($requisitionsWithUsageLogs->isNotEmpty()) {
            DB::table('requisitions')
                ->whereIn('req_id', $requisitionsWithUsageLogs)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'processed_at' => DB::raw('NOW()')
                ]);
        }
    }

    public function down(): void
    {
        // This is a data fix migration, no rollback needed
    }
};
