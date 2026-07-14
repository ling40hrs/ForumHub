<?php

declare(strict_types=1);

namespace App\Middleware;

class Auth
{
    // Wire: read Bearer token from $_SERVER['HTTP_AUTHORIZATION'],
    // validate it, and return the authenticated user id or null (401 on failure).
    public function handle(): ?int
    {
        return null;
    }
}
