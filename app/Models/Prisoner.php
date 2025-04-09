<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prisoner extends Model
{
    use HasFactory;

    protected $fillable = [
        'roepnaam',
        'tussenvoegsel',
        'achternaam',
        'straat',
        'huisnummer',
        'toevoeging',
        'postcode',
        'woonplaats',
        'bsn',
        'delict',
        'foto',
        'geboortedatum',
    ];

    protected $casts = [
        'geboortedatum' => 'date',
    ];

    public function cells()
    {
        return $this->belongsToMany(Cell::class, 'cell_prisoners')
            ->withPivot(['datum_start', 'datum_eind', 'tijd_start', 'tijd_eind', 'verslag_bewaker'])
            ->withTimestamps();
    }

    public function currentCell()
    {
        return $this->belongsToMany(Cell::class, 'cell_prisoners')
            ->wherePivotNull('datum_eind')
            ->withPivot(['datum_start', 'tijd_start', 'verslag_bewaker'])
            ->withTimestamps()
            ->first();
    }

    public function movements()
    {
        return $this->hasMany(CellMovement::class);
    }

    public function getFullNameAttribute()
    {
        $name = $this->roepnaam ?? '';
        
        if ($this->tussenvoegsel) {
            $name .= ' ' . $this->tussenvoegsel;
        }
        
        $name .= ' ' . $this->achternaam;
        
        return trim($name);
    }
}
