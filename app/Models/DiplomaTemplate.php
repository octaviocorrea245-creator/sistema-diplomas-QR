<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaTemplate extends Model
{
    protected $table = 'diploma_templates';

    protected $fillable = [
        'curso_id',
        'nombre',
        'background_image',
        'canvas_width',
        'canvas_height',
    ];

    public function curso()
    {
        return $this->belongsTo(Cursos::class, 'curso_id');
    }

    public function elements()
    {
        return $this->hasMany(DiplomaTemplateElement::class, 'template_id')->orderBy('orden');
    }

    public function diplomas()
    {
        return $this->hasMany(Diploma::class, 'template_id');
    }
}
