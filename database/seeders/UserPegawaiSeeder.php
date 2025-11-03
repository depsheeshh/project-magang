<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class UserPegawaiSeeder extends Seeder
{
    public function run(): void
    {
        $pegawaiData = [
            ['name' => 'Ma\'ruf Nuryasa', 'email' => 'maruf@dkis.go.id', 'nip' => '198001010001', 'telepon' => '0811111111', 'bidang_id' => 1, 'jabatan_id' => 1],
            ['name' => 'Asep Komara', 'email' => 'asep@dkis.go.id', 'nip' => '198001010002', 'telepon' => '0811111112', 'bidang_id' => 1, 'jabatan_id' => 2],
            ['name' => 'Aria Dipahandi', 'email' => 'aria@dkis.go.id', 'nip' => '198001010003', 'telepon' => '0811111113', 'bidang_id' => 2, 'jabatan_id' => 3],
            ['name' => 'Eka Purnomo Sidik', 'email' => 'eka@dkis.go.id', 'nip' => '198001010004', 'telepon' => '0811111114', 'bidang_id' => 2, 'jabatan_id' => 4],
            ['name' => 'Herro Yudhistira', 'email' => 'herro@dkis.go.id', 'nip' => '198001010005', 'telepon' => '0811111115', 'bidang_id' => 3, 'jabatan_id' => 5],
            ['name' => 'Monang M.T. Situmorang', 'email' => 'monang@dkis.go.id', 'nip' => '198001010006', 'telepon' => '0811111116', 'bidang_id' => 3, 'jabatan_id' => 6],
            ['name' => 'Linda Suminar', 'email' => 'linda@dkis.go.id', 'nip' => '198001010007', 'telepon' => '0811111117', 'bidang_id' => 4, 'jabatan_id' => 7],
            ['name' => 'Hendy Hermawan', 'email' => 'hendy@dkis.go.id', 'nip' => '198001010008', 'telepon' => '0811111118', 'bidang_id' => 4, 'jabatan_id' => 8],
            ['name' => 'Dodi Solihudin', 'email' => 'dodi@dkis.go.id', 'nip' => '198001010009', 'telepon' => '0811111119', 'bidang_id' => 5, 'jabatan_id' => 9],
            ['name' => 'Indra Gunawan', 'email' => 'indra@dkis.go.id', 'nip' => '198001010010', 'telepon' => '0811111120', 'bidang_id' => 5, 'jabatan_id' => 10],
        ];

        foreach ($pegawaiData as $p) {
            // Insert ke tabel users (pakai Eloquent biar bisa assign role)
            $user = User::create([
                'name'              => $p['name'],
                'email'             => $p['email'],
                'password'          => Hash::make('password'),
                'instansi_id'       => 18,
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]);

            // Assign role pegawai
            $user->assignRole('pegawai');

            // Insert ke tabel pegawai
            DB::table('pegawai')->insert([
                'user_id'    => $user->id,
                'bidang_id'  => $p['bidang_id'],
                'jabatan_id' => $p['jabatan_id'],
                'nip'        => $p['nip'],
                'telepon'    => $p['telepon'],
                'created_id' => 1,
                'updated_id' => null,
                'deleted_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ]);
        }
    }
}
