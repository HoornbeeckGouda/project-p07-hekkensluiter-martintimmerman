<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        // Existing update logic
    }

    public function destroy(Request $request)
    {
        // Existing destroy logic
    }

    public function toggleTwoFactor(Request $request)
    {
        $user = Auth::user();
        $user->two_factor_enabled = !$user->two_factor_enabled;
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Two-factor authentication ' . ($user->two_factor_enabled ? 'enabled' : 'disabled'));
    }
}