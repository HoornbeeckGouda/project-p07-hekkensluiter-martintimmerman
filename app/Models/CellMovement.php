<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CellMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'prisoner_id',
        'from_cell_id',
        'to_cell_id',
        'datum_start',
        'datum_eind',
        'reden',
    ];

    protected $casts = [
        'datum_start' => 'date',
        'datum_eind' => 'date',
    ];

    public function prisoner()
    {
        return $this->belongsTo(Prisoner::class);
    }

    public function fromCell()
    {
        return $this->belongsTo(Cell::class, 'from_cell_id');
    }

    public function toCell()
    {
        return $this->belongsTo(Cell::class, 'to_cell_id');
    }
    
    public function bewaker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}