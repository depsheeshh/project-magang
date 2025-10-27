<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rapat;

class AutoEndRapat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rapat:end-auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menandai rapat yang sudah lewat waktu selesai sebagai selesai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Rapat::where('status', 'berjalan')
            ->where('waktu_selesai', '<', now())
            ->update(['status' => 'selesai']);

        $this->info("Berhasil menandai {$count} rapat sebagai selesai.");
    }
}
