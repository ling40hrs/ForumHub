<?php

declare(strict_types=1);

return [
    'name'      => 'ForumHub',
    'env'       => $_ENV['APP_ENV'] ?? 'development',
    'debug'     => (bool) ($_ENV['APP_DEBUG'] ?? true),
    'url'       => $_ENV['APP_URL'] ?? 'http://localhost:8000',
    'jwt_secret' => $_ENV['JWT_SECRET'] ?? 'change-me-in-production',
];
