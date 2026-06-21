<?php

namespace Meraki\Packages\Auth\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Meraki\Packages\Auth\Contracts\AuthManager;

class LoginController extends Controller
{
    public function __construct(private AuthManager $auth) {}

    public function create()
    {
        return view('meraki-auth::auth.login');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $result = $this->auth->platform('web')->login($credentials, ['remember' => $request->boolean('remember')]);

        if (!$result->success()) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended($result->data()['redirect'] ?? config('meraki-auth.platforms.web.redirects.login', '/dashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
