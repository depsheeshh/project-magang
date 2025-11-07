<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            // ✅ Admin full akses
            'admin' => Permission::all()->pluck('name')->toArray(),

            // ✅ Frontliner
            'frontliner' => [
                'visits.view',
                'visits.approve',
                'visits.reject',
                'visits.checkout',
            ],

            // ✅ Pegawai
            'pegawai' => [
                'pegawai.visits.view',
                'pegawai.visits.details',
                'pegawai.rapat.view',

                // Rapat & Instansi
                'rapat.view',
                'rapat.manage',
                'rapat.invite',
                'rapat.rekap',
                'instansi.manage',
            ],

            // ✅ Tamu
            'tamu' => [
                'tamu.visits.view',
                'tamu.visits.create',
                'tamu.visits.checkout',
                'tamu.profile.update',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($permissions);
        }
    }
}
