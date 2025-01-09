<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/{provider}/redirect', function (string $provider) {
    return Socialite::driver($provider)->redirect();
});

Route::get('/auth/{provider}/callback', function (string $provider) {
    $providerUser = Socialite::driver($provider)->user();

    $user = User::updateOrCreate([
        'email' => $providerUser->email,
    ], [
        'provider_id' => $providerUser->id,
        'provider_name' => $provider,
        'name' => $providerUser->name,
        'provider_nickname' => $providerUser->nickname,
        'provider_avatar' => $providerUser->avatar,
        'provider_token' => $providerUser->token,
        'provider_refresh_token' => $providerUser->refreshToken,
    ]);

    Auth::login($user);

    return redirect('/logged');
});

Route::get('/logged', function() {

    dd(Auth::user());

});
