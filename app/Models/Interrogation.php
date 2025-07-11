<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interrogation extends Model
{
    use HasFactory;

    protected $fillable = [
        'prisoner_id',
        'user_id',
        'datum_tijd',
        'verslag',
        'bijlage',
        'bijlage_type',
    ];

    protected $casts = [
        'datum_tijd' => 'datetime',
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