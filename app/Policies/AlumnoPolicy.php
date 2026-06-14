<?php

namespace App\Policies;

use App\Models\Curso;
use App\Models\User;

class AlumnoPolicy
{
    // El supervisor puede ver cualquier alumno
    // El admin solo puede ver alumnos de su departamento
    public function view(User $auth, User $alumno): bool
    {
        if ($auth->hasRole('Supervisor')) {
            return true;
        }

        return $auth->hasRole('Admin') && $auth->department_id === $alumno->department_id;
    }

    // El admin solo puede gestionar alumnos en cursos de su departamento
    public function manageCurso(User $auth, Curso $curso): bool
    {
        if ($auth->hasRole('Supervisor')) {
            return true;
        }

        return $auth->hasRole('Admin') && $auth->department_id === $curso->departamento_id;
    }
}
