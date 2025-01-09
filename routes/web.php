<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
    dd($providerUser);

    $user = User::updateOrCreate([
        'email' => $providerUser->email,
    ], [
        'provider_name' => $provider,
        'name' => $providerUser->name,
        'provider_id' => $providerUser->id,
        'provider_nickname' => $providerUser->nickname,
        'provider_avatar' => $providerUser->avatar,
        'provider_token' => $providerUser->token,
        'provider_refresh_token' => $providerUser->refreshToken,
        'id_token' => $providerUser->accessTokenResponseBody['id_token'] ?? null
    ]);

    Auth::login($user);

    return redirect('/me');
});

Route::get('/me', function() {

    $user = Auth::user();

    return response()->json(['data' => $user], 200);

});

Route::get('/logout', function () {

    $realm = config('services.keycloak.realms');
    $keycloakBaseUrl = config('services.keycloak.base_url');

    // URL for logging out of Keycloak
    $keycloakLogoutUrl = "{$keycloakBaseUrl}/realms/{$realm}/protocol/openid-connect/logout";

    // Recuperar o token de ID do usuário autenticado
    $idToken = Auth::user()->id_token; // Ajuste conforme o local de armazenamento do token

    // Fazer a requisição de logout
    $response = Http::asForm()->post($keycloakLogoutUrl, [
        'id_token_hint' => $idToken, // Token de ID do usuário autenticado
    ]);

    // Verificar se o logout foi bem-sucedido
    if ($response->successful()) {
        // dd("hasoudhahsdso");
        // Realizar logout local no Laravel
        Auth::logout();

        // Redirecionar para a página inicial
        return redirect('/');
    }

    // Caso ocorra erro, retornar com mensagem de erro
    return response()->json([
        'message' => 'Logout no Keycloak falhou.',
        'error' => $response->body(),
    ], 500);
});
