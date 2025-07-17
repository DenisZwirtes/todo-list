<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Relacionamento: Um usuário pode ter várias tarefas (Many to Many).
     */
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user', 'user_id', 'task_id')->withTimestamps();
    }


    /**
     * Relacionamento: Um usuário tem muitas categorias (One to Many).
     */
    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }


    public function setPasswordAttribute($value)
    {
        // Só aplica o hash se não estiver hasheado
        if (strlen($value) === 60 && (strpos($value, '$2y$') === 0 || strpos($value, '$2b$') === 0)) {
            $this->attributes['password'] = $value;
        } else {
        $this->attributes['password'] = Hash::make($value);
        }
    }
}
