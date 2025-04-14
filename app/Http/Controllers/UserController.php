<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        // Alleen admins en directeuren mogen gebruikers beheren
        $this->middleware('role:admin,directeur')->only(['create', 'store', 'edit', 'update', 'destroy']);
        $this->middleware('auth');
    }

    //lijst van alle gebruikers met hun rollen
    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }
    
    // nieuwe gebruiker aan maken
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }
    
    // Sla nieuwe gebruiker op
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
        
        // Maak gebruiker aan
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        
        // Koppel rollen aan gebruiker
        $user->roles()->attach($validated['roles']);
        
        return redirect()->route('users.index')
            ->with('success', 'Gebruiker succesvol aangemaakt.');
    }
    
    // formulier bestaande gebruiker te bewerken
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        
        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }
    
    //  de gegevens van een gebruiker bij
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id',
        ]);
        
        // Alleen wachtwoord updaten als een nieuwe is 
        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];
        
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }
        
        $user->update($userData);
        $user->roles()->sync($validated['roles']);
        
        return redirect()->route('users.index')
            ->with('success', 'Gebruiker succesvol bijgewerkt.');
    }
    
    // Verwijder een gebruiker behalve als ingelogd  is
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Je kunt je eigen account niet verwijderen.');
        }
        
        $user->roles()->detach();
        $user->delete();
        
        return redirect()->route('users.index')
            ->with('success', 'Gebruiker succesvol verwijderd.');
    }
}
