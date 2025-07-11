<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrisonerLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'prisoner_id',
        'user_id',
        'log_type',
        'description',
        'log_date',
    ];

    protected $casts = [
        'log_date' => 'datetime',
    ];

    public function prisoner()
    {
        return $this->belongsTo(Prisoner::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}