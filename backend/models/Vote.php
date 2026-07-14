<?php

declare(strict_types=1);

namespace App\Models;

class Vote
{
    public function __construct(private \PDO $pdo)
    {
    }

    // Wire queries against `votes` (user_id, target_id, target_type, value).
}
