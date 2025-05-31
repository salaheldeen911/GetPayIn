<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\ProcessScheduledPosts::class,
        Commands\CleanOrphanedImages::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Process scheduled posts every minute
        $schedule->command('posts:process-scheduled')
            ->everyMinute()
            ->withoutOverlapping()
            ->onOneServer();

        // Clean up orphaned images daily at midnight
        $schedule->command('images:clean')
            ->daily()
            ->at('00:00')
            ->withoutOverlapping();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
