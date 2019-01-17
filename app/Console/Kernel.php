<?php

namespace App\Console;

use App\Console\Commands\ImportCustomers;
use App\Console\Commands\ImportOrders;
use App\Console\Commands\ImportProducts;
use App\Console\Commands\UpdateProducts;
use App\Console\Commands\UpdateOrders;
use App\Console\Commands\UpdateCustomers;
use App\Console\Commands\DeleteOrders;
use App\Console\Commands\Multipass;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ImportOrders::class,
	    ImportCustomers::class,
	    Multipass::class,
        ImportProducts::class,
        UpdateProducts::class,
        UpdateOrders::class,
        UpdateCustomers::class,
        DeleteOrders::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
