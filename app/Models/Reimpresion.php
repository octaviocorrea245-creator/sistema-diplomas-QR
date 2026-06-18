<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reimpresion extends Model
{
    use HasFactory;

    protected $table = 'reimpresiones_diploma';

    protected $fillable = [
        'diploma_id',
        'user_id',
        'motivo',
    ];

    public function diploma()
    {
        return $this->belongsTo(Diploma::class, 'diploma_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
