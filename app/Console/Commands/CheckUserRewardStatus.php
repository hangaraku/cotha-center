<?php

namespace App\Console\Commands;

use App\Models\UserReward;
use Illuminate\Console\Command;

class CheckUserRewardStatus extends Command
{
    protected $signature = 'debug:check-reward-status';
    protected $description = 'Check user reward status values in database';

    public function handle()
    {
        $this->info('Checking user_rewards table status values...');
        
        $orders = UserReward::all();
        
        if ($orders->isEmpty()) {
            $this->warn('No orders found in database.');
            return;
        }
        
        $this->info("Found {$orders->count()} orders:");
        
        foreach ($orders as $order) {
            $statusType = gettype($order->getRawOriginal('status'));
            $statusValue = $order->getRawOriginal('status');
            
            $this->line("Order ID: {$order->id} | Status: {$statusValue} | Type: {$statusType}");
        }
        
        $this->info("\nStatus distribution:");
        $distribution = UserReward::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
            
        foreach ($distribution as $item) {
            $this->line("Status '{$item->status}': {$item->count} orders");
        }
    }
}

