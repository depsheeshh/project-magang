<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'admin' => Permission::all()->pluck('name')->toArray(), // full akses

            'frontliner' => [
                'visits.view',
                'visits.approve',
                'visits.reject',
                'visits.checkout',
            ],

            'pegawai' => [
                'pegawai.visits.view',
                'pegawai.visits.details',
                'pegawai.rapat.view'
            ],

            'tamu' => [
                'tamu.visits.view',
                'tamu.visits.create',
                'tamu.visits.checkout',
                'tamu.profile.update',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($permissions);
            }
        }
    }
}
