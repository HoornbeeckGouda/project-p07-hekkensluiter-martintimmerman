<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TwoFactorCode;
use App\Notifications\TwoFactorCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    public function show()
    {
        // Check if user has started 2FA process
        if (!session('two_factor_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        // Check if user has started 2FA process
        $userId = session('two_factor_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        // Rate limiting for 2FA attempts
        $key = 'two-factor:' . $userId . '|' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'code' => "Te veel pogingen. Probeer het over {$seconds} seconden opnieuw.",
            ]);
        }

        // Find the user and their 2FA code
        $user = User::findOrFail($userId);
        $twoFactorCode = TwoFactorCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$twoFactorCode) {
            RateLimiter::hit($key);
            
            throw ValidationException::withMessages([
                'code' => 'De verificatiecode is ongeldig of verlopen.',
            ]);
        }

        // Clear rate limiter
        RateLimiter::clear($key);

        // Delete the used code
        $twoFactorCode->delete();

        // Get remember preference
        $remember = session('two_factor_remember', false);

        // Clear 2FA session
        session()->forget(['two_factor_user_id', 'two_factor_remember']);

        // Log the user in
        Auth::login($user, $remember);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Generate and send 2FA code to user
     */
    public static function generateAndSendCode(User $user)
    {
        TwoFactorCode::where('user_id', $user->id)->delete();

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        TwoFactorCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(10), 
        ]);

        $user->notify(new TwoFactorCodeNotification($code));
    }
}