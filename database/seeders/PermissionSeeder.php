<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $permissions = [
            // User Management
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            // Role Management
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',

            // Permission Management
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',

            // Pegawai Management
            'pegawai.view',
            'pegawai.create',
            'pegawai.update',
            'pegawai.delete',
            'pegawai.rapat.view',

            // Bidang Management
            'bidang.view',
            'bidang.create',
            'bidang.update',
            'bidang.delete',

            // Jabatan Management
            'jabatan.view',
            'jabatan.create',
            'jabatan.update',
            'jabatan.delete',

            // Kunjungan
            'visits.view',
            'visits.create',
            'visits.update',
            'visits.delete',
            'visits.approve',
            'visits.reject',
            'visits.checkout',

            // Pegawai (kunjungan assigned)
            'pegawai.visits.view',
            'pegawai.visits.details',

            // Tamu
            'tamu.visits.view',
            'tamu.visits.create',
            'tamu.visits.checkout',
            'tamu.profile.update',

            // Laporan
            'reports.view',
            'reports.export',

            // History Logs
            'logs.view',
            'logs.delete', // opsional kalau mau bisa hapus log

            // Survey
            'surveys.view',
            'surveys.delete',

            // Rapat
            'rapat.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
