<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Jabatan;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Kepala Dinas Komunikasi, Informatika dan Statistik',
            'Sekretaris',
            'Kepala Bidang Persandian dan Keamanan Informasi',
            'Kepala Bidang Layanan E Government',
            'Kepala Bidang Statistik Sektoral',
            'Kepala Bidang Infrastruktur Teknologi Informasi dan Komunikasi',
            'Kepala Sub Bagian Umum dan Kepegawaian',
            'Kepala Sub Bagian Program dan Pelaporan',
            'Pranata Komputer Ahli Muda',
            'Sandiman Ahli Muda',
            'Statistisi Ahli Muda',
            'Pranata Hubungan Masyarakat Ahli Muda',
            'Analis Keuangan Pusat dan Daerah Ahli Muda',
            'Analis Kebijakan Ahli Muda',
            'Penelaah Teknis Kebijakan',
            'Penata Layanan Operasional',
            'Pengolah Data dan Informasi',
            'Pranata Komputer Ahli Pertama',
            'Fasilitator Pemerintahan',
            'Surveyor Pemetaan Terampil',
            'Pengadministrasi Perkantoran',
            'Pengelola Layanan Operasional',
            'Penata Hubungan Masyarakat Ahli Pertama',
            'Operator Layanan Operasional',
        ];

        foreach ($data as $nama) {
            Jabatan::firstOrCreate(['nama_jabatan' => $nama]);
        }
    }
}
