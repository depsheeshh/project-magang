<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Instansi;
use App\Models\User;

class InstansiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan instansi DKIS ada
        $instansi = Instansi::firstOrCreate(
            ['nama_instansi' => 'DKIS Kota Cirebon'],
            [
                'lokasi'     => 'Jl. DR. Sudarsono No.40, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134',
                'created_id' => 1, // opsional, bisa isi id admin pertama
            ]
        );

        // Update semua user dengan role pegawai agar otomatis punya instansi DKIS
        User::whereHas('roles', fn($q) => $q->where('name', 'pegawai'))
            ->update(['instansi_id' => $instansi->id]);
    }
}
