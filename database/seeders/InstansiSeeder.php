<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Instansi;
use App\Models\User;

class InstansiSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_instansi' => 'KOTA CIREBON', 'alias' => 'PEMKOT', 'jenis' => 'instansi'],
            ['nama_instansi' => 'SEKRETARIAT DAERAH', 'alias' => 'SETDA', 'jenis' => 'instansi'],
            ['nama_instansi' => 'SEKRETARIAT DPRD', 'alias' => 'SETWAN', 'jenis' => 'instansi'],
            ['nama_instansi' => 'INSPEKTORAT DAERAH', 'alias' => 'INSPEKTORAT', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS PENDIDIKAN', 'alias' => 'DISDIK', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS KESEHATAN', 'alias' => 'DINKES', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS PEKERJAAN UMUM DAN TATA RUANG', 'alias' => 'DPUTR', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS PERUMAHAN RAKYAT DAN KAWASAN PERMUKIMAN', 'alias' => 'DPRKP', 'jenis' => 'instansi'],
            ['nama_instansi' => 'SATUAN POLISI PAMONG PRAJA', 'alias' => 'SATPOL-PP', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS PEMADAM KEBAKARAN DAN PENYELAMATAN', 'alias' => 'DPKP', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS SOSIAL', 'alias' => 'DINSOS', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS TENAGA KERJA', 'alias' => 'DISNAKER', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS KETAHANAN PANGAN, PERTANIAN DAN PERIKANAN', 'alias' => 'DKP3', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS LINGKUNGAN HIDUP', 'alias' => 'DLH', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL', 'alias' => 'DISDUKCAPIL', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS PEMBERDAYAAN PEREMPUAN, PERLINDUNGAN ANAK, PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA', 'alias' => 'DP3APPKB', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS PERHUBUNGAN', 'alias' => 'DISHUB', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS KOMUNIKASI, INFORMATIKA DAN STATISTIK', 'alias' => 'DKIS', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS KOPERASI, USAHA KECIL, MENENGAH, PERDAGANGAN DAN PERINDUSTRIAN', 'alias' => 'DKUKMPP', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU', 'alias' => 'DPMPTSP', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS KEBUDAYAAN DAN PARIWISATA', 'alias' => 'DISBUDPAR', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS PEMUDA DAN OLAHRAGA', 'alias' => 'DISPORA', 'jenis' => 'instansi'],
            ['nama_instansi' => 'DINAS PERPUSTAKAAN DAN KEARSIPAN', 'alias' => 'DISPUSIP', 'jenis' => 'instansi'],
            ['nama_instansi' => 'BADAN PERENCANAAN PEMBANGUNAN, PENELITIAN DAN PENGEMBANGAN DAERAH', 'alias' => 'BAPPELITBANGDA', 'jenis' => 'instansi'],
            ['nama_instansi' => 'BADAN PENGELOLA KEUANGAN DAN PENDAPATAN DAERAH', 'alias' => 'BPKPD', 'jenis' => 'instansi'],
            ['nama_instansi' => 'BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA', 'alias' => 'BKPSDM', 'jenis' => 'instansi'],
            ['nama_instansi' => 'BADAN PENANGGULANGAN BENCANA DAERAH', 'alias' => 'BPBD', 'jenis' => 'instansi'],
            ['nama_instansi' => 'BADAN KESATUAN BANGSA DAN POLITIK', 'alias' => 'BAKESBANGPOL', 'jenis' => 'instansi'],
            ['nama_instansi' => 'KECAMATAN HARJAMUKTI', 'alias' => 'KECHARJAMUKTI', 'jenis' => 'instansi'],
            ['nama_instansi' => 'KECAMATAN LEMAHWUNGKUK', 'alias' => 'KECLMHWUNGKUK', 'jenis' => 'instansi'],
            ['nama_instansi' => 'KECAMATAN KEJAKSAN', 'alias' => 'KECKEJAKSAN', 'jenis' => 'instansi'],
            ['nama_instansi' => 'KECAMATAN KESAMBI', 'alias' => 'KECKESAMBI', 'jenis' => 'instansi'],
            ['nama_instansi' => 'KECAMATAN PEKALIPAN', 'alias' => 'KECPEKALIPAN', 'jenis' => 'instansi'],
            ['nama_instansi' => 'RSD GUNUNG JATI', 'alias' => 'RSDGJ', 'jenis' => 'instansi'],
            ['nama_instansi' => 'KELURAHAN ARGASUNYA', 'alias' => 'KELARGASUNYA', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN DRAJAT', 'alias' => 'KELDRAJAT', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN HARJAMUKTI', 'alias' => 'KELHARJAMUKTI', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN JAGASATRU', 'alias' => 'KELJAGASATRU', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN KALIJAGA', 'alias' => 'KELKALIJAGA', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN KARYAMULYA', 'alias' => 'KELKARYAMULYA', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN KEBON BARU', 'alias' => 'KELKEBONBARU', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN KECAPI', 'alias' => 'KELKECAPI', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN KEJAKSAN', 'alias' => 'KELKEJAKSAN', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN KESAMBI', 'alias' => 'KELKESAMBI', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN KESENDEN', 'alias' => 'KELKESENDEN', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN KESEPUHAN', 'alias' => 'KELKESEPUHAN', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN LARANGAN', 'alias' => 'KELLARANGAN', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN LEMAHWUNGKUK', 'alias' => 'KELLMHWUNGKUK', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN PANJUNAN', 'alias' => 'KELPANJUNAN', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN PEGAMBIRAN', 'alias' => 'KELPEGAMBIRAN', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN PEKALANGAN', 'alias' => 'KELPEKALANGAN', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN PEKALIPAN', 'alias' => 'KELPEKALIPAN', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN PEKIRINGAN', 'alias' => 'KELPEKIRINGAN', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN PULASAREN', 'alias' => 'KELPULASAREN', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN SUKAPURA', 'alias' => 'KELSUKAPURA', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'KELURAHAN SUNYARAGI', 'alias' => 'KELSUNYARAGI', 'jenis' => 'kelurahan'],
            ['nama_instansi' => 'PUSKESMAS ASTANAGARIB', 'alias' => 'PKMASTANAGARIB', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS CANGKOL', 'alias' => 'PKMCANGKOL', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS DRAJAT', 'alias' => 'PKMDRAJAT', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS GUNUNGSARI', 'alias' => 'PKMGUNUNGSARI', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS JAGASATRU', 'alias' => 'PKMJAGASATRU', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS JALANKEMBANG', 'alias' => 'PKMJALANKEMBANG', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS KALIJAGAPERMAI', 'alias' => 'PKMKALIJAGAPERMAI', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS KALITANJUNG', 'alias' => 'PKMKALITANJUNG', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS KEJAKSAN', 'alias' => 'PKMKEJAKSAN', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS KESAMBI', 'alias' => 'PKMKESAMBI', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS KESUNEAN', 'alias' => 'PKMKESUNEAN', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS LARANGAN', 'alias' => 'PKMLARANGAN', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS MAJASEM', 'alias' => 'PKMMAJASEM', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS NELAYAN', 'alias' => 'PKMNELAYAN', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS PAMITRAN', 'alias' => 'PKMPAMITRAN', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS PEGAMBIRAN', 'alias' => 'PKMPEGAMBIRAN', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS PEKALANGAN', 'alias' => 'PKMPEKALANGAN', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS PERUMNASUTARA', 'alias' => 'PKMPERUMNASUTARA', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS PESISIR', 'alias' => 'PKMPESISIR', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS PULASAREN', 'alias' => 'PKMPULASAREN', 'jenis' => 'puskesmas'],
            ['nama_instansi' => 'PUSKESMAS SITOPENG', 'alias' => 'PKMSITOPENG', 'jenis' => 'puskesmas'],
        ];

        foreach ($data as $instansi) {
            Instansi::updateOrCreate(
                ['nama_instansi' => $instansi['nama_instansi']],
                array_merge($instansi, [
                    'is_active' => true,
                    'created_id' => 1,
                    'updated_id' => 1,
                ])
            );
        }

        // Relasi otomatis: semua pegawai diarahkan ke DKIS
        $dkis = Instansi::where('alias', 'DKIS')->first();
        if ($dkis) {
            User::whereHas('roles', fn($q) => $q->where('name', 'pegawai'))
                ->update(['instansi_id' => $dkis->id]);
        }
    }
}
