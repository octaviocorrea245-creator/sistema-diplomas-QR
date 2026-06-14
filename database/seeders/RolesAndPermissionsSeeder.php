<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::firstOrCreate(['name' => 'crear diploma']);
        Permission::firstOrCreate(['name' => 'editar diploma']);
        Permission::firstOrCreate(['name' => 'ver diploma']);
        Permission::firstOrCreate(['name' => 'gestionar usuarios']);

        $supervisor   = Role::firstOrCreate(['name' => 'supervisor']);
        $admin        = Role::firstOrCreate(['name' => 'admin']);
        $beneficiario = Role::firstOrCreate(['name' => 'beneficiario']);

        $supervisor->givePermissionTo(Permission::all());
        $admin->givePermissionTo(['crear diploma', 'editar diploma', 'ver diploma']);
        $beneficiario->givePermissionTo(['ver diploma']);
    }
}