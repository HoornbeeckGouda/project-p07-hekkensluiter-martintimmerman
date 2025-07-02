<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Admin permissions
        $adminPermissions = [
            ['name' => 'admin.access', 'description' => 'Toegang tot beheermodule', 'group' => 'admin'],
            ['name' => 'admin.users.view', 'description' => 'Gebruikers bekijken', 'group' => 'admin'],
            ['name' => 'admin.users.create', 'description' => 'Gebruikers aanmaken', 'group' => 'admin'],
            ['name' => 'admin.users.edit', 'description' => 'Gebruikers bewerken', 'group' => 'admin'],
            ['name' => 'admin.users.delete', 'description' => 'Gebruikers verwijderen', 'group' => 'admin'],
            ['name' => 'admin.roles.manage', 'description' => 'Rollen beheren', 'group' => 'admin'],
            ['name' => 'admin.permissions.manage', 'description' => 'Rechten beheren', 'group' => 'admin'],
            ['name' => 'admin.settings', 'description' => 'Systeeminstellingen', 'group' => 'admin'],
            
        ];

        // Prisoner permissions
        $prisonerPermissions = [
            ['name' => 'prisoners.view', 'description' => 'Gedetineerden bekijken', 'group' => 'prisoners'],
            ['name' => 'prisoners.create', 'description' => 'Gedetineerden aanmaken', 'group' => 'prisoners'],
            ['name' => 'prisoners.edit', 'description' => 'Gedetineerden bewerken', 'group' => 'prisoners'],
            ['name' => 'prisoners.delete', 'description' => 'Gedetineerden verwijderen', 'group' => 'prisoners'],
            ['name' => 'prisoners.move', 'description' => 'Gedetineerden verplaatsen', 'group' => 'prisoners'],
            ['name' => 'prisoners.release', 'description' => 'Gedetineerden vrijlaten', 'group' => 'prisoners'],
            ['name' => 'prisoners.logs.view', 'description' => 'Logboeken bekijken', 'group' => 'prisoners'],
            ['name' => 'prisoners.logs.create', 'description' => 'Logboeken toevoegen', 'group' => 'prisoners'],
            ['name' => 'prisoners.logs.delete', 'description' => 'Logboeken verwijderen', 'group' => 'prisoners'],
        ];

        // Cell permissions
        $cellPermissions = [
            ['name' => 'cells.view', 'description' => 'Cellen bekijken', 'group' => 'cells'],
            ['name' => 'cells.create', 'description' => 'Cellen aanmaken', 'group' => 'cells'],
            ['name' => 'cells.edit', 'description' => 'Cellen bewerken', 'group' => 'cells'],
            ['name' => 'cells.delete', 'description' => 'Cellen verwijderen', 'group' => 'cells'],
        ];

        // User permissions
        $userPermissions = [
            ['name' => 'users.view', 'description' => 'Gebruikers bekijken', 'group' => 'users'],
            ['name' => 'users.create', 'description' => 'Gebruikers aanmaken', 'group' => 'users'],
            ['name' => 'users.edit', 'description' => 'Gebruikers bewerken', 'group' => 'users'],
            ['name' => 'users.delete', 'description' => 'Gebruikers verwijderen', 'group' => 'users'],
        ];

        // Reports permissions
        $reportPermissions = [
            ['name' => 'reports.view', 'description' => 'Rapporten bekijken', 'group' => 'reports'],
            ['name' => 'reports.export', 'description' => 'Rapporten exporteren', 'group' => 'reports'],
            ['name' => 'logs.view', 'description' => 'Systeemlogboeken bekijken', 'group' => 'reports'],
        ];

        // Alle permissions toevoegen
        $allPermissions = array_merge(
            $adminPermissions,
            $prisonerPermissions,
            $cellPermissions,
            $userPermissions,
            $reportPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::create($permission);
        }

        // Permissions toewijzen aan rollen
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles()
    {
        // Admin krijgt alle rechten
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all();
            $adminRole->permissions()->sync($allPermissions->pluck('id'));
        }

        // Directeur krijgt bijna alle rechten
        $directeurRole = Role::where('name', 'directeur')->first();
        if ($directeurRole) {
            $directeurPermissions = Permission::whereNotIn('name', [
                'admin.permissions.manage',
                'admin.settings'
            ])->get();
            $directeurRole->permissions()->sync($directeurPermissions->pluck('id'));
        }

        // Coordinator krijgt operationele rechten
        $coordinatorRole = Role::where('name', 'coordinator')->first();
        if ($coordinatorRole) {
            $coordinatorPermissions = Permission::whereIn('name', [
                'prisoners.view',
                'prisoners.create',
                'prisoners.edit',
                'prisoners.move',
                'prisoners.release',
                'prisoners.logs.view',
                'prisoners.logs.create',
                'cells.view',
                'cells.create',
                'cells.edit',
                'users.view',
                'reports.view',
                'logs.view'
            ])->get();
            $coordinatorRole->permissions()->sync($coordinatorPermissions->pluck('id'));
        }

        // Bewaker krijgt beperkte rechten
        $bewakerRole = Role::where('name', 'bewaker')->first();
        if ($bewakerRole) {
            $bewakerPermissions = Permission::whereIn('name', [
                'prisoners.view',
                'prisoners.logs.view',
                'prisoners.logs.create',
                'cells.view'
            ])->get();
            $bewakerRole->permissions()->sync($bewakerPermissions->pluck('id'));
        }
    }
}