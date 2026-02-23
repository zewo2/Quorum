<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'progress',
        'input_payload',
        'result_payload',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'input_payload' => 'array',
        'result_payload' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
