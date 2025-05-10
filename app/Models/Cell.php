<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cell extends Model
{
    use HasFactory;

    protected $fillable = [
        'afdeling',
        'celnummer',
    ];

    public function prisoners()
    {
        return $this->belongsToMany(Prisoner::class, 'cell_prisoners')
            ->withPivot(['datum_start', 'datum_eind', 'tijd_start', 'tijd_eind', 'verslag_bewaker'])
            ->withTimestamps();
    }

    public function currentPrisoners()
    {
        return $this->belongsToMany(Prisoner::class, 'cell_prisoners')
            ->wherePivotNull('datum_eind')
            ->withPivot(['datum_start', 'tijd_start', 'verslag_bewaker'])
            ->withTimestamps();
    }
public function isOccupied()
{
    return $this->currentPrisoners()->count() > 0;
}
}
