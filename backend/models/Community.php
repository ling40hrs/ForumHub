<?php

declare(strict_types=1);

namespace App\Models;

class Community
{
    public function __construct(private \PDO $pdo)
    {
    }

    // Wire queries against `communities` (id, name, slug, description, owner_id, created_at).
}
