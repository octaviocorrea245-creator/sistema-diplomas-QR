<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CursoUsuario extends Pivot
{
    protected $table = 'curso_usuario';

    protected $fillable = [
        'curso_id',
        'user_id',
        'estado',
        'fecha_completado',
    ];

    protected $casts = [
        'fecha_completado' => 'date',
    ];

    // Estados posibles del alumno en el curso
    const ESTADO_INSCRITO   = 'inscrito';
    const ESTADO_EN_CURSO   = 'en_curso';
    const ESTADO_COMPLETADO = 'completado';
    const ESTADO_BAJA       = 'baja';

    public static function estados(): array
    {
        return [
            self::ESTADO_INSCRITO,
            self::ESTADO_EN_CURSO,
            self::ESTADO_COMPLETADO,
            self::ESTADO_BAJA,
        ];
    }
}
