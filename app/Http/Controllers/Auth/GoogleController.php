<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        $redirectUrl = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'url' => $redirectUrl
        ],201);
    }


    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = User::where('email', $googleUser->email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(str()->random(16)), // Random password
                ]);
            }
            $token = $user->createToken('GoogleAuth')->plainTextToken;

            cookie('auth_token', $token, 60, "/", "127.0.0.1", false, true);
            $frontendUrl = 'http://localhost:5173/check-performance';
            return redirect($frontendUrl);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Authentication failed'], 401);
        }
    }

    public function getAuthToken(Request $request)
    {
        return response()->json([
            'token' => $request->cookie('auth_token')
        ],201);
    }
}
