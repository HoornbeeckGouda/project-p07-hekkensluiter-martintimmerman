<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_name',
        'id_document_type',
        'id_document_number',
        'arrival_time',
        'departure_time',
        'visit_purpose',
        'prisoner_id',
        'created_by',
    ];

    protected $casts = [
        'arrival_time' => 'datetime',
        'departure_time' => 'datetime',
    ];

    public function prisoner()
    {
        return $this->belongsTo(Prisoner::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}