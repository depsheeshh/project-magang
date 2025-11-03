<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ruangan;
use Carbon\Carbon;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            [
                'nama_ruangan'      => 'Mini Command Center (MCC)',
                'id_kantor'         => 2, // ID kantor DKIS Kesambi
                'kapasitas_maksimal'=> 30,
                'dipakai'           => false,
                'created_id'        => 1,
                'updated_id'        => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'nama_ruangan'      => 'Co-Working Space (CWS)',
                'id_kantor'         => 2, // ID kantor DKIS Kesambi
                'kapasitas_maksimal'=> 50,
                'dipakai'           => false,
                'created_id'        => 1,
                'updated_id'        => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'nama_ruangan'      => 'Laboratorium Komputer (LABKOM)',
                'id_kantor'         => 1, // ID kantor DKIS Bypass
                'kapasitas_maksimal'=> 40,
                'dipakai'           => false,
                'created_id'        => 1,
                'updated_id'        => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ];

        foreach ($data as $row) {
            Ruangan::updateOrCreate(
                ['nama_ruangan' => $row['nama_ruangan'], 'id_kantor' => $row['id_kantor']],
                $row
            );
        }
    }
}
