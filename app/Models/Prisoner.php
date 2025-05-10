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
        'datum_arrestatie',
        'datum_in_bewaring',
    ];

    protected $casts = [
        'geboortedatum' => 'date',
        'datum_arrestatie' => 'date', 
        'datum_in_bewaring' => 'date',
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
    public function volledigeNaam()
{
    return $this->roepnaam 
        . ($this->tussenvoegsel ? ' ' . $this->tussenvoegsel : '') 
        . ' ' . $this->achternaam;
}


    public function movementHistory()
{
    return $this->hasMany(CellMovement::class, 'prisoner_id');
}
    public function logs()
    {
        return $this->hasMany(PrisonerLog::class);
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