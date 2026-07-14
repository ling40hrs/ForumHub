<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;

class VoteController
{
    // POST /votes — cast/retract a vote. Wire: upsert into `votes`, recompute score.
    public function store(): void
    {
    }
}
