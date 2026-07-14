<?php

declare(strict_types=1);

namespace App\Models;

class Comment
{
    public function __construct(private \PDO $pdo)
    {
    }

    // Wire queries against `comments` (id, body, user_id, post_id, parent_id, score, created_at).
}
