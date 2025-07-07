<?php

namespace App\Console\Commands;

use App\Http\Controllers\NetoController;
use Illuminate\Console\Command;


class Orders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bunnings:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = new NetoController();
        $orders->fetchBunningsOrders();
        $this->info('Bunnings orders fetched and saved.');
    }
}
