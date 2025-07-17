<?php

namespace App\Console\Commands;

use App\Models\Log;
use Illuminate\Console\Command;

class CleanOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:clean {--days=30 : Número de dias para manter os logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove logs antigos do sistema';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Removendo logs mais antigos que {$days} dias...");

        $deletedCount = Log::where('created_at', '<', $cutoffDate)->delete();

        $this->info("{$deletedCount} logs foram removidos.");

        return Command::SUCCESS;
    }
}
