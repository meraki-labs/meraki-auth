<?php

namespace Meraki\Packages\Auth\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Meraki\Packages\Auth\Contracts\AuthManager;

class RegisterController extends Controller
{
    public function __construct(private AuthManager $auth) {}

    public function create()
    {
        return view('meraki-auth::auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $result = $this->auth->platform('web')->register($data);
        $user = $result->data()['user'];
        event(new Registered($user));
        Auth::login($user);

        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            return redirect()->route('verification.notice');
        }

        return redirect($result->data()['redirect'] ?? config('meraki-auth.platforms.web.redirects.register', '/dashboard'));
    }
}
