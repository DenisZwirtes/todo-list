<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    protected $fillable = [
        'level',
        'message',
        'error_message',
        'context',
        'file',
        'line',
        'ip_address',
        'user_agent',
        'user_id'
    ];

    protected $casts = [
        'context' => 'array',
        'line' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
