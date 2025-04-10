<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'log_date',
        'related_model',
        'related_id',
    ];

    protected $casts = [
        'log_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}