<?php

declare(strict_types=1);

namespace App\Models;

class Post
{
    public function __construct(private \PDO $pdo)
    {
    }

    // Wire queries against `posts` (id, title, body, user_id, community_id, score, created_at).
}
