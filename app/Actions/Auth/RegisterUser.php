<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Hash;

class RegisterUser
{
    /**
     * Create a new class instance.
     */
    public function __construct(private readonly Hasher $hasher, private Dispatcher $dispatcher)
    {
        //
    }

    public function execute(array $data)
    {
        $user = User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $this->hasher->make($data['password']),
            ]
        );

        $this->dispatcher->dispatch(new Registered($user));

        return $user;
    }
}
