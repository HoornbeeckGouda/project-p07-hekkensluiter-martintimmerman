<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\TwoFactorController;
use Laravel\Fortify\Fortify;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }
    
    public function login(LoginRequest $request)
    {
        $user = Auth::getProvider()->retrieveByCredentials(
            $request->only(Fortify::username(), 'password')
        );
        
        if (!$user || !Auth::getProvider()->validateCredentials($user, $request->only(Fortify::username(), 'password'))) {
            return back()->withErrors(['email' => __('Deze inloggegevens komen niet overeen met onze gegevens.')]);
        }
        
        if ($user->two_factor_enabled) {
            session([
                'two_factor_user_id' => $user->id, 
                'two_factor_remember' => $request->has('remember')
            ]);
            
            TwoFactorController::generateAndSendCode($user);
            
            return redirect()->route('two-factor.login')
                ->with('status', 'Er is een verificatiecode naar je e-mailadres verzonden. Controleer je inbox en voer de code in om door te gaan.');
        }
        
        Auth::login($user, $request->has('remember'));
        return redirect()->intended(route('dashboard'));
    }
}