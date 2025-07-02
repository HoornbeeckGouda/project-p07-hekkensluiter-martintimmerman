<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'group', // Groepering voor permissions (bijv. 'users', 'prisoners', 'cells')
    ];

    /**
     * Relatie met rollen
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Groepeer permissions voor het beheerder overzicht
     */
    public static function groupedPermissions()
    {
        return self::all()->groupBy('group');
    }
}