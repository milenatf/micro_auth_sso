<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SsoController extends Controller
{
    public function redirectToKeycloak(string $provider, Request $request)
    {
        // dd("Redirect to keycloak: " . $provider);
        // return Socialite::driver($provider)->redirect();

        dd($request->all());
        $user = Socialite::driver('keycloak')->user();

        // this line will be needed if you have an exist Eloquent database User
        // then you can user user data gotten from keycloak to query such table
        // and proceed
        $existingUser = User::where('email', $user->email)->first();

        // ... your desire implementation comes here

        return redirect()->intended('/whatever-your-route-look-like');
    }
}
