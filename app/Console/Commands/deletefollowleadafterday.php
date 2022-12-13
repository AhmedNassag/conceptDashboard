<?php

namespace App\Console\Commands;

use App\Models\followLead;
use App\Models\Order;
use Illuminate\Console\Command;

class deletefollowleadafterday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'follow lead:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete follow lead after day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Order::with('user.client')->where(function ($query) {
            $query->where('order_status_id', '=', 5)
                ->orWhere('order_status_id', '=', 7);
            })->where('updated_at', '>=', now()->subDays(2)->toDateTimeString())
            ->get();
        foreach ($orders as $order)
        {
            followLead::create([
                'name' => $order->user->name,
                'address' => $order->user->client->address,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
                'seller_category_id' => 1,
            ]);
        }
//        return 0;
    }
}
