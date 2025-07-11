<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:admin.users.view')->only(['index']);
        $this->middleware('permission:admin.users.create')->only(['create', 'store']);
        $this->middleware('permission:admin.users.edit')->only(['edit', 'update']);
        $this->middleware('permission:admin.users.delete')->only(['destroy', 'bulkAction']);
    }

    /**
     * Display a listing of the users with search and filters
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role != '') {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status == 'active');
        }

        $query->orderBy('name', 'asc');

        $users = $query->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'boolean',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => $validated['is_active'] ?? true,
            'two_factor_enabled' => true,
        ]);

        $user->roles()->sync($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Gebruiker succesvol aangemaakt.');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'boolean',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $validated['is_active'] ?? true,
            'two_factor_enabled' => true, // Behoud 2FA
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);
        $user->roles()->sync($validated['roles']);

        return redirect()->route('admin.users.index')
            ->with('success', 'Gebruiker succesvol bijgewerkt.');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Je kunt je eigen account niet verwijderen.');
        }

        $user->roles()->detach();
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Gebruiker succesvol verwijderd.');
    }

    /**
     * Handle bulk actions for users
     */
    public function bulkAction(Request $request)
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
                
                if (count($filteredIds) > 0) {
                    // Detach roles eerst
                    foreach ($filteredIds as $userId) {
                        $user = User::find($userId);
                        if ($user) {
                            $user->roles()->detach();
                        }
                    }
                    
                    User::whereIn('id', $filteredIds)->delete();
                    $message = count($filteredIds) . ' gebruiker(s) verwijderd.';
                } else {
                    $message = 'Geen gebruikers verwijderd. Je kunt jezelf niet verwijderen.';
                }
                break;
        }

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }
}