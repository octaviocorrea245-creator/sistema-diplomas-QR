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
                'password'      => bcrypt('password123'),
                'role'          => 'beneficiario',
                'department_id' => null,
            ]
        );

        $beneficiario->assignRole('beneficiario');

        // ── Admins de ejemplo (uno por departamento) ──
        $depts = Departamento::all()->keyBy(fn($d) => mb_strtolower($d->name));

        $ejemplos = [
            ['username' => 'admin_iaev', 'full_name' => 'Carlos Mendoza',  'keyword' => 'animac'],
            ['username' => 'admin_ibio', 'full_name' => 'Laura Gutiérrez', 'keyword' => 'biotecn'],
            ['username' => 'admin_tid',  'full_name' => 'Rodrigo Flores',  'keyword' => 'tecnolog'],
            ['username' => 'admin_ima',  'full_name' => 'Diana Ramírez',   'keyword' => 'manufactura'],
            ['username' => 'admin_cia',  'full_name' => 'Marco Sánchez',   'keyword' => 'comercio'],
        ];

        foreach ($ejemplos as $ej) {
            $dept = $depts->first(fn($d) => str_contains(mb_strtolower($d->name), $ej['keyword']));
            $u = User::firstOrCreate(
                ['username' => $ej['username']],
                [
                    'full_name'     => $ej['full_name'],
                    'password'      => bcrypt('password123'),
                    'role'          => 'admin',
                    'department_id' => $dept?->id,
                ]
            );
            $u->assignRole('admin');
        }
    }
}
