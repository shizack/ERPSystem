<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Requisition;

class CheckRequisitions extends Command
{
    protected $signature = 'check:requisitions';
    protected $description = 'Check requisitions in database';

    public function handle()
    {
        $total = Requisition::count();
        $this->info("Total requisitions: $total");
        
        $pending = Requisition::where('status', 'pending')->count();
        $this->info("Pending: $pending");
        
        $approved = Requisition::where('status', 'approved')->count();
        $this->info("Approved: $approved");
        
        $rejected = Requisition::where('status', 'rejected')->count();
        $this->info("Rejected: $rejected");
        
        $this->info("\nAll requisitions:");
        Requisition::all()->each(function($req) {
            $this->info("ID: {$req->req_id}, Status: {$req->status}, Product: {$req->product_id}, Approved By: {$req->approved_by}");
        });
    }
}
