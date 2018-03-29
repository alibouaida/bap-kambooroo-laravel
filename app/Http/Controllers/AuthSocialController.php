<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthSocialController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $providerUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            throw $e;
        }

        $user = $this->checkIfProviderIdExists($provider, $providerUser->id);

        if ($user) {
            Auth::guard()->login($user, true);
            return redirect('/');
        }

        if ($providerUser->email !== null) {

            $user = User::where('email', $providerUser->email)->first();
            if ($user) {
                $field = $provider . '_id';
                $user->$field = $providerUser->id;
                $user->save();
                Auth::guard()->login($user, true);
                return redirect('/');
            }
        }

        $user = User::create([
            'name' => $providerUser->name,
            'email' => $providerUser->email,
            $provider . '_id' => $providerUser->id,
        ]);

        if ($user) Auth::guard()->login($user, true);
        return redirect('/');

    }

    public function checkIfProviderIdExists($provider, $providerId)
    {

        $field = $provider . "_id";

        $user = User::where($field, $providerId)->first();

        return $user;
    }
}
