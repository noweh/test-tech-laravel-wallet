<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

readonly class PerformUserCreation
{
    public function execute(string $name, string $email, string $hashedPassword): User
    {
        $user = User::create([
            'name' => $name,
            'email' => strtolower($email),
            'password' => $hashedPassword,
        ]);

        $user->wallet()->create([
            'balance' => 0,
        ]);

        return $user;
    }
}