<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use App\Models\Prisoner;
use App\Models\Cell;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin.access');
    }

    /**
     * Dashboard voor beheerders
     */
    public function dashboard()
    {
        $statistics = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_prisoners' => Prisoner::count(),
            'occupied_cells' => Cell::whereHas('currentPrisoners')->count(),
            'total_cells' => Cell::count(),
            'total_roles' => Role::count(),
            'total_permissions' => Permission::count(),
        ];

        $recent_users = User::with('roles')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('statistics', 'recent_users'));
    }

    /**
     * Gebruikersbeheer overzicht
     */
    public function users(Request $request)
    {
        $query = User::with('roles');

        // Zoekfunctionaliteit
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter op rol
        if ($request->has('role') && $request->role != '') {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter op status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $users = $query->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Rollenbeheer overzicht
     */
    public function roles()
    {
        $roles = Role::withCount('users', 'permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Permissionsbeheer - vinkjes overzicht
     */
    public function permissions()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::groupedPermissions();
        
        return view('admin.permissions.index', compact('roles', 'permissions'));
    }

    /**
     * Update permissions voor een rol
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Sync permissions met de rol
        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('admin.permissions')
            ->with('success', "Rechten voor rol '{$role->name}' succesvol bijgewerkt.");
    }

    /**
     * Systeem instellingen
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Bulk acties voor gebruikers
     */
    public function bulkUserAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $users = User::whereIn('id', $validated['user_ids'])->get();

        switch ($validated['action']) {
            case 'activate':
                User::whereIn('id', $validated['user_ids'])->update(['is_active' => true]);
                $message = count($users) . ' gebruiker(s) geactiveerd.';
                break;
            
            case 'deactivate':
                User::whereIn('id', $validated['user_ids'])->update(['is_active' => false]);
                $message = count($users) . ' gebruiker(s) gedeactiveerd.';
                break;
            
            case 'delete':
                // Voorkom dat admin zichzelf verwijdert
                $filteredIds = array_filter($validated['user_ids'], function($id) {
                    return $id != auth()->id();
                });
                
                User::whereIn('id', $filteredIds)->delete();
                $message = count($filteredIds) . ' gebruiker(s) verwijderd.';
                break;
        }

        return redirect()->route('admin.users')
            ->with('success', $message);
    }
}