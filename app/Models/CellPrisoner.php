<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CellPrisoner extends Model
{
    use HasFactory;

    protected $table = 'cell_prisoners';

    protected $fillable = [
        'datum_start',
        'datum_eind',
        'tijd_start',
        'tijd_eind',
        'prisoner_id',
        'cell_id',
        'verslag_bewaker',
    ];

    protected $casts = [
        'datum_start' => 'date',
        'datum_eind' => 'date',
        'tijd_start' => 'datetime',
        'tijd_eind' => 'datetime',
    ];

    public function prisoner()
    {
        return $this->belongsTo(Prisoner::class);
    }

    public function cell()
    {
        return $this->belongsTo(Cell::class);
    }
    
}