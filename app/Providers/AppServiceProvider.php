<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $loginRateLimitResponse = function (Request $request) {
            if($request->expectsJson())
            {
                return response()->json(
                    [
                        'email' => 'These credentials do not match our records!',
                    ],
                    429
                );
            }

            return back()
                ->withErrors(['email' => 'These credentials do not match our records!'])
                ->withInput($request->except(['password']));
        };

        RateLimiter::for('login', function(Request $request) use ($loginRateLimitResponse) {
           return [
                Limit::perMinute(100)->by($request->ip())->response($loginRateLimitResponse),

               Limit::perMinute(5)->by($request->get('email'))
                   ->response($loginRateLimitResponse),
           ];
        });

        RateLimiter::for('password-reset-request', function(Request $request)  {
            return [
                Limit::perMinute(100)->by($request->ip()),

                Limit::perMinute(3)->by($request->get('email')),
            ];
        });

        RateLimiter::for('password-reset', function(Request $request)  {
            return [
                Limit::perMinute(100)->by($request->ip()),

                Limit::perMinute(3)->by($request->get('email')),
            ];
        });

        Password::defaults(function(){
           if($this->app->isLocal())
           {
               return Password::min(6);
           }

           return Password::min(8)
               ->mixedCase()
               ->uncompromised()
               ->letters()
               ->numbers()
               ->symbols();
        });

    }
}
