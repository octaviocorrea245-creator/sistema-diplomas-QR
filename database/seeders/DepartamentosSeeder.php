<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento; 

class DepartamentosSeeder extends Seeder
{
    public function run(): void
    {
        $departamentos = [
            [
                'name' => 'Ingeniería en Tecnologías de la Información',
                'abreviatura' => 'ITI',
                'descripccion' => 'Carrera de TI',
            ],
            [
                'name' => 'Ingeniería en Animacion y Efectos Visuales',
                'abreviatura' => 'IAEV',
                'descripccion' => 'Carrera IAEV',
            ],
            [
                'name' => 'Ingeniería en Biotecnología',
                'abreviatura' => 'IBIO',
                'descripccion' => 'Carrera IBIO',
            ],
            [
                'name' => 'Ingeniería en Tecnologías de Manufactura',
                'abreviatura' => 'ITM',
                'descripccion' => 'Carrera ITM',
            ],
            [
                'name' => 'Licenciatura en Comercio Internacional y Aduanas',
                'abreviatura' => 'CIA',
                'descripccion' => 'Carrera CIA',
            ],
        ];

        foreach ($departamentos as $departamento) {
            Departamento::firstOrCreate(
                ['abreviatura' => $departamento['abreviatura']],
                $departamento
            );
        }
    }
}