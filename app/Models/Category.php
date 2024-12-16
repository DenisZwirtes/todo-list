<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
    ];

    /**
     * Relacionamento: Uma categoria pertence a um usuário (Many to One).
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Uma categoria tem muitas tarefas (One to Many).
     *
     * @return HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Mutator: Normaliza o nome da categoria ao definir.
     *
     * @param string $value
     */
    public function setNameAttribute($value): void
    {
        $this->attributes['name'] = ucfirst(trim($value));
    }

    /**
     * Scope: Filtra categorias por usuário autenticado.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwnedByUser($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
