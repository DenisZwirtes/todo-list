<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Relacionamento: Uma categoria pertence a um usuário (Many to One).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Uma categoria tem muitas tarefas (One to Many).
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
