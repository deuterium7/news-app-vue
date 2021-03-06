<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /**
     * Авторизация.
     *
     * @param $provider
     *
     * @return mixed
     */
    public function login($provider)
    {
        try {
            return Socialite::with($provider)->redirect();
        } catch (\InvalidArgumentException $e) {
            return redirect('/login');
        }
    }

    /**
     * Получить ответ от провайдера.
     *
     * @param SocialAccountService $service
     * @param $provider
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(SocialAccountService $service, $provider)
    {
        $driver = Socialite::driver($provider);
        $user = $service->getUser($driver, $provider);
        auth()->login($user, true);

        return redirect('/');
    }
}
