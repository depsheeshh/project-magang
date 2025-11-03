<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KantorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kantor')->insert([
            [
                'nama_kantor' => 'DKIS Bypass',
                'alamat'      => 'Jl. Brigjend Dharsono No.1, Sunyaragi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45135',
                'latitude'    => -6.7254818,
                'longitude'   => 108.5389768,
                'created_id'  => null,
                'updated_id'  => null,
                'deleted_id'  => null,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ],
            [
                'nama_kantor' => 'DKIS Kesambi',
                'alamat'      => 'Jl. DR. Sudarsono No.40, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134',
                'latitude'    => -6.7281094,
                'longitude'   => 108.5527149,
                'created_id'  => null,
                'updated_id'  => null,
                'deleted_id'  => null,
                'created_at'  => now(),
                'updated_at'  => now(),
                'deleted_at'  => null,
            ],
        ]);
    }
}
