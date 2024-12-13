<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relacionamento: Um usuário tem muitas tarefas (One to Many).
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Relacionamento: Um usuário tem muitas categorias (One to Many).
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }


    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
