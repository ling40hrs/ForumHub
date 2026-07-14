<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;

class UserController
{
    // GET /users/{id} — public profile. Wire: find by id, 404 if missing.
    public function show(): void
    {
    }

    // PUT /users/{id} — update profile. Wire: authorize owner, update bio/avatar.
    public function update(): void
    {
    }
}
