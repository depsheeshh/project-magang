<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Jalankan seeder roles, permissions, dan mapping
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            BidangSeeder::class,
            JabatanSeeder::class,
            InstansiSeeder::class,
            KantorSeeder::class,
            UserPegawaiSeeder::class,
            RuanganSeeder::class,
        ]);

        // Buat user default admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@yahoo.com'],
            [
                'name'              => 'Admin',
                'password'          => Hash::make('Admin123!'), // password kompleks
                'status'            => 1, // Active
                'instansi_id'       => 18,
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]
        );
        $admin->assignRole('admin');

        // User default frontliner
        $frontliner = User::firstOrCreate(
            ['email' => 'frontliner@example.com'],
            [
                'name'              => 'Frontliner',
                'password'          => Hash::make('Front123!'),
                'status'            => 1,
                'instansi_id'       => 18,
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]
        );
        $frontliner->assignRole('frontliner');

        // User default pegawai
        $pegawai = User::firstOrCreate(
            ['email' => 'pegawai@example.com'],
            [
                'name'              => 'Pegawai',
                'password'          => Hash::make('Pegawai123!'),
                'status'            => 1,
                'instansi_id'       => 18,
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]
        );
        $pegawai->assignRole('pegawai');

        // ✅ Tambahkan juga ke tabel pegawai
        DB::table('pegawai')->updateOrInsert(
            ['user_id' => $pegawai->id], // supaya tidak double insert
            [
                'bidang_id'  => 3,              // isi default, bisa disesuaikan
                'jabatan_id' => 4,              // isi default, bisa disesuaikan
                'nip'        => '02111929344', // contoh NIP unik
                'telepon'    => '082123456789',   // contoh telepon unik
                'created_id' => 1,
                'updated_id' => null,
                'deleted_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'deleted_at' => null,
            ]
        );

        // User default tamu (Inactive contoh)
        $tamu = User::firstOrCreate(
            ['email' => 'tamu@example.com'],
            [
                'name'              => 'Tamu',
                'password'          => Hash::make('Tamu123!'),
                'status'            => 0, // Inactive
                'email_verified_at' => Carbon::now(),
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ]
        );
        $tamu->assignRole('tamu');
    }
}
