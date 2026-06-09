<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Departamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Roles y permisos
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Departamentos
        $this->call(DepartamentosSeeder::class);

        $primerDepartamento = Departamento::first();

        // Supervisor
        $supervisor = User::firstOrCreate(
            ['username' => 'supervisor'],
            [
                'full_name'     => 'Supervisor General',
                'email'         => 'supervisor@example.com',
                'password'      => bcrypt('password123'),
                'role'          => 'supervisor',
                'department_id' => null,
            ]
        );

        $supervisor->assignRole('supervisor');

        // Admin
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'full_name'     => 'Administrador del Sistema',
                'email'         => 'admin@example.com',
                'password'      => bcrypt('password123'),
                'role'          => 'admin',
                'department_id' => $primerDepartamento?->id,
            ]
        );

        $admin->assignRole('admin');

        // Beneficiario
        $beneficiario = User::firstOrCreate(
            ['username' => 'beneficiario'],
            [
                'full_name'     => 'Beneficiario de Prueba',
                'email'         => 'beneficiario@example.com',
                'password'      => bcrypt('password123'),
                'role'          => 'beneficiario',
                'department_id' => null,
            ]
        );

        $beneficiario->assignRole('beneficiario');
    }
}