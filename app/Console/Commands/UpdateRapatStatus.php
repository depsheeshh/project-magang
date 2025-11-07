<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rapat;

class UpdateRapatStatus extends Command
{
    protected $signature = 'rapat:update-status';
    protected $description = 'Update status rapat berdasarkan waktu_mulai dan waktu_selesai';

    public function handle(): void
    {
        $now = now();

        // Update rapat yang belum dimulai
        Rapat::where('status', '!=', 'dibatalkan')
            ->where('waktu_mulai', '>', $now)
            ->update(['status' => 'belum_dimulai']);

        // Update rapat yang sedang berjalan
        Rapat::where('status', '!=', 'dibatalkan')
            ->where('waktu_mulai', '<=', $now)
            ->where('waktu_selesai', '>=', $now)
            ->update(['status' => 'berjalan']);

        // Update rapat yang sudah selesai
        Rapat::where('status', '!=', 'dibatalkan')
            ->where('waktu_selesai', '<', $now)
            ->update(['status' => 'selesai']);

        $this->info('Status rapat berhasil diperbarui.');
    }
}
