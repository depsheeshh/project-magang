<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        ]);

        // Buat user default admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@yahoo.com'],
            [
                'name'              => 'Admin',
                'password'          => Hash::make('Admin123!'), // password kompleks
                'status'            => 1, // Active
                'email_verified_at' => now(), // langsung verified
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
                'email_verified_at' => now(),
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
                'email_verified_at' => now(),
            ]
        );
        $pegawai->assignRole('pegawai');

        // User default tamu (Inactive contoh)
        $tamu = User::firstOrCreate(
            ['email' => 'tamu@example.com'],
            [
                'name'              => 'Tamu',
                'password'          => Hash::make('Tamu123!'),
                'status'            => 0, // Inactive
                'email_verified_at' => now(),
            ]
        );
        $tamu->assignRole('tamu');
    }
}
