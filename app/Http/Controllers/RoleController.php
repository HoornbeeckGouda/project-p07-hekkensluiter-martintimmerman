<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Middleware wordt nu geregeld in web.php routes, dus verwijder deze regels
    }

    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->paginate(10);
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'required|string|max:500',
        ]);

        Role::create($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol succesvol aangemaakt.');
    }

    public function show(Role $role)
    {
        $role->load(['users', 'permissions']);
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        // Voorkom bewerking van systeem rollen
        $systemRoles = ['admin', 'directeur', 'coordinator', 'bewaker'];
        if (in_array($role->name, $systemRoles)) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Systeem rollen kunnen niet worden bewerkt.');
        }

        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $systemRoles = ['admin', 'directeur', 'coordinator', 'bewaker'];
        if (in_array($role->name, $systemRoles)) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Systeem rollen kunnen niet worden bewerkt.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'required|string|max:500',
        ]);

        $role->update($validated);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol succesvol bijgewerkt.');
    }

    public function destroy(Role $role)
    {
        $systemRoles = ['admin', 'directeur', 'coordinator', 'bewaker'];
        if (in_array($role->name, $systemRoles)) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Systeem rollen kunnen niet worden verwijderd.');
        }

        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Rol kan niet worden verwijderd omdat er nog gebruikers aan gekoppeld zijn.');
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Rol succesvol verwijderd.');
    }

    public function permissions(Role $role)
    {
        $permissions = Permission::all()->groupBy('group');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.permissions', compact('role', 'permissions', 'rolePermissions'));
    }

    public function updatePermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->permissions()->sync($validated['permissions'] ?? []);

        return redirect()->route('admin.roles.permissions', $role)
            ->with('success', 'Permissies succesvol bijgewerkt.');
    }
}