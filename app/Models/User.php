<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // ← este es el que falta

class User extends Authenticatable
{
<<<<<<< HEAD
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles; // ← HasRoles viene del use de arriba

    protected $fillable = [
        'name',
        'email',
        'password',
=======
    use Notifiable, HasRoles;

    protected $fillable = [
        'full_name',
        'username',
        'email',
        'password',
        'role',
        'department_id',
>>>>>>> master
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

<<<<<<< HEAD
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
=======
    public function department()
    {
        return $this->belongsTo(Departamento::class);
>>>>>>> master
    }
}