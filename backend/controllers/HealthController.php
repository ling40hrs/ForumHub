<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;

class HealthController
{
    // GET / — readiness probe. Wire: return Response::json(['status' => 'ok']).
    public function index(): void
    {
    }
}
