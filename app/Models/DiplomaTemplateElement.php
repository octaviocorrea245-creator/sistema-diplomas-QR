<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiplomaTemplateElement extends Model
{
    protected $table = 'diploma_template_elements';

    protected $fillable = [
        'template_id',
        'tipo',
        'variable',
        'x',
        'y',
        'width',
        'height',
        'config_json',
        'orden',
    ];

    protected $casts = [
        'config_json' => 'array',
        'x' => 'float',
        'y' => 'float',
        'width' => 'float',
        'height' => 'float',
    ];

    public function template()
    {
        return $this->belongsTo(DiplomaTemplate::class, 'template_id');
    }
}
