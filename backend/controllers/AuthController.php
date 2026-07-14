<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;

class AuthController
{
    // POST /auth/register — create user. Wire: validate, hash password, return token/user.
    public function register(): void
    {
    }

    // POST /auth/login — authenticate. Wire: verify password, return token/user.
    public function login(): void
    {
    }
}
