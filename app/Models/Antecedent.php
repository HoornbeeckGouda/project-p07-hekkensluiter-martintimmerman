<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antecedent extends Model
{
    use HasFactory;

    protected $fillable = [
        'prisoner_id',
        'delict',
        'datum_delict',
        'beschrijving',
        'bewijsmateriaal',
        'bewijsmateriaal_type',
    ];

    protected $casts = [
        'datum_delict' => 'date',
    ];

    public function prisoner()
    {
        return $this->belongsTo(Prisoner::class);
    }
}