<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DiseñadorRoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $diseñador = Role::firstOrCreate(['name' => 'diseñador']);

        $diseñador->givePermissionTo(['crear diploma', 'editar diploma', 'ver diploma']);
    }
}
