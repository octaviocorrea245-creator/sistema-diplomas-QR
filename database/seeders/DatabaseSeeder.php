<?php

namespace Database\Seeders;

use App\Models\User;
<<<<<<< HEAD
=======
use App\Models\Departamento;
>>>>>>> master
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

<<<<<<< HEAD
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(RolesAndPermissionsSeeder::class);
        
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
    
}
=======
    public function run(): void
    {
        // 1. Roles y permisos primero
        $this->call(RolesAndPermissionsSeeder::class);

        // 2. Departamentos
        $this->call(DepartamentosSeeder::class);

        // Tomar cualquier departamento para los usuarios de prueba
        $primerDepartamento = Departamento::first();

        // ── Supervisor ──────────────────────────────────────────
        $supervisor = User::firstOrCreate(
            ['username' => 'supervisor'], // Buscamos por username único en lugar de email
            [
                'full_name'     => 'Supervisor General',
                'password'      => bcrypt('password123'),
                'role'          => 'supervisor',
                'department_id' => null, // el supervisor no requiere departamento
            ]
        );
        $supervisor->assignRole('supervisor');

        // ── Admin ────────────────────────────────────────────────
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

        // ── Beneficiario ─────────────────────────────────────────
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
    }
}
>>>>>>> master
