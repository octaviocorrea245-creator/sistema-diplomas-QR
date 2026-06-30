<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndSupervisorSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'supervisor']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'beneficiario']);

        $supervisor = User::firstOrCreate(
            ['email' => 'supervisor@sistema.com'],
            [
                'name'          => 'Supervisor Principal',
                'username'      => 'supervisor',
                'password'      => Hash::make('password'),
                'role'          => 'supervisor',
                'department_id' => null,
            ]
        );

        $supervisor->assignRole('supervisor');
    }
}