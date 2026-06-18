<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Departamento;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'full_name',
        'username',
        'email',
        'password',
        'role',
        'department_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function department()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function cursos()
    {
        return $this->belongsToMany(Cursos::class, 'curso_usuario', 'user_id', 'curso_id')
                    ->withPivot('estado', 'fecha_completado')
                    ->withTimestamps();
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?? $this->username ?? '—';
    }

    public function scopeAlumnos(Builder $query): Builder
    {
        return $query->where('role', 'beneficiario');
    }

    public function scopeDelDepartamento(Builder $query, $departamentoId): Builder
    {
        return $query->where('department_id', $departamentoId);
    }
}
