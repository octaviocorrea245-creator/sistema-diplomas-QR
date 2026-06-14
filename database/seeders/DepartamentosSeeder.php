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
                'color' => '#03A9F4',
            ],
            [
                'name' => 'Ingeniería en Animacion y Efectos Visuales',
                'abreviatura' => 'IAEV',
                'descripccion' => 'Carrera IAEV',
                'color' => '#F5A623',
            ],
            [
                'name' => 'Ingeniería en Biotecnología',
                'abreviatura' => 'IBIO',
                'descripccion' => 'Carrera IBIO',
                'color' => '#7EC441',
            ],
            [
                'name' => 'Ingeniería en Tecnologías de Manufactura',
                'abreviatura' => 'ITM',
                'descripccion' => 'Carrera ITM',
                'color' => '#E53935',
            ],
            [
                'name' => 'Licenciatura en Comercio Internacional y Aduanas',
                'abreviatura' => 'CIA',
                'descripccion' => 'Carrera CIA',
                'color' => '#8E44AD',
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