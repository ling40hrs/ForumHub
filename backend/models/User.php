<?php

declare(strict_types=1);

namespace App\Models;

class User
{
    public function __construct(private \PDO $pdo)
    {
    }

    // Wire queries against `users` (id, username, email, password, avatar, bio, karma, ...).
}
