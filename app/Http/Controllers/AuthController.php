<?php

namespace App\Http\Controllers;

use App\Actions\Auth\RegisterUser;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    public function __construct(
        private readonly AuthManager $auth,
        private readonly Redirector $redirector,
        private readonly UrlGenerator $url,
        private readonly Factory $view
    )
    {

    }

    public function showLoginForm(): View
    {
        return  $this->view->make('auth.login');
    }

    public function showRegistrationForm(): View
    {
        return $this->view->make('auth.register');
    }

    public function login(LoginRequest $request)
    {
        if($this->auth->attempt($request->only(['email','password']), $request->boolean('remember')))
        {
            session()->regenerate();

            return $this->redirector->intended(route('dashboard', absolute: false));
        }

        return $this->redirector->back()
            ->withErrors(['email' => 'These credentials do not match our records!'])
            ->withInput($request->except('password'));
    }

    public function register(RegisterRequest $request, RegisterUser $registerUser)
    {
        $validated = $request->validated();

        $user = $registerUser->execute($validated);

        $this->auth->login($user);

        return $this->redirector->intended($this->url->route('dashboard', absolute: false));
    }

    public function logout(Request $request)
    {
        $this->auth->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->redirector->to('/');
    }
}
