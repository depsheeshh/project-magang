<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Bidang;

class BidangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama_bidang' => 'Sekretariat',
                'deskripsi'   => 'Membantu Kepala Dinas dalam pembinaan dan pemberian layanan administrasi penyusunan perencanaan, penatausahaan, keuangan, sumber daya manusia Aparatur, kerumahtanggaan, arsip dan perpustakaan, organisasi dan tatalaksana, kerjasama, hubungan masyarakat, protokol, pengelolaan barang milik daerah/negara dan dokumentasi Dinas serta melaksanakan pengoordinasian penyusunan peraturan perundang-undangan dan bantuan hukum dalam penyelenggaraan tugas Dinas.',
            ],
            [
                'nama_bidang' => 'Bidang Infrastruktur Teknologi Informasi dan Komunikasi',
                'deskripsi'   => 'Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standard, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan pengelolaan domain, penatalaksanaan dan pengawasan serta sistem jaringan informatika.',
            ],
            [
                'nama_bidang' => 'Bidang Layanan E-Government',
                'deskripsi'   => 'Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standar, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan tata kelola e-government, pengembangan ekosistem e-government serta pengembangan aplikasi.',
            ],
            [
                'nama_bidang' => 'Bidang Pengelolaan Informasi dan Komunikasi Publik',
                'deskripsi'   => 'Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standard, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan pengelolaan dan layanan informasi, pengelolaan komunikasi serta hubungan masyarakat.',
            ],
            [
                'nama_bidang' => 'Bidang Persandian dan Keamanan Informasi',
                'deskripsi'   => 'Membantu Kepala Dinas dalam memimpin dan menyelenggarakan tugas urusan pemerintahan bidang persandian dan keamanan informasi meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standar, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan persandian, keamanan informasi serta layanan keamanan informasi.',
            ],
            [
                'nama_bidang' => 'Bidang Statistik Sektoral',
                'deskripsi'   => 'Membantu Kepala Dinas meliputi perumusan kebijakan, koordinasi dan sinkronisasi, penyusunan norma, standard, prosedur dan kriteria, pemberian bimbingan teknis dan supervisi, pemantauan, analisis, evaluasi dan pelaporan sub bidang urusan pengumpulan dan analisis data, sumber daya statistik sektor serta layanan dan penyebarluasan data.',
            ],
        ];

        foreach ($data as $item) {
            Bidang::firstOrCreate(
                ['nama_bidang' => $item['nama_bidang']],
                ['deskripsi' => $item['deskripsi']]
            );
        }
    }
}
