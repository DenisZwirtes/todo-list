<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_completed',
        'completed_at',
        'user_id',
        'category_id',
    ];

    /**
     * Relacionamento: Uma tarefa pertence a um usuário (Many to One).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Uma tarefa pertence a uma categoria (Many to One).
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
