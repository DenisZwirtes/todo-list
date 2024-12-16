<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_completed',
        'completed_at',
        'category_id',
    ];

    /**
     * Relacionamento: Uma tarefa pode pertencer a vários usuários (Many to Many).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id')->withTimestamps();
    }


    /**
     * Relacionamento: Uma tarefa pertence a uma categoria (Many to One).
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
