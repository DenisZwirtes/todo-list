<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        \App\Console\Commands\HashUserPasswords::class,
        \App\Console\Commands\RehashUserPasswords::class,
    ];


    protected function schedule(Schedule $schedule): void
    {

        $schedule->command('tasks:delete-old-completed')->daily();
    }


    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
