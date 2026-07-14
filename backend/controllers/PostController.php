<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Response;

class PostController
{
    // GET /posts — list posts (optionally by community). Wire: query `posts`.
    public function index(): void
    {
    }

    // GET /posts/{id} — single post. Wire: find by id, Response::error(404) if missing.
    public function show(): void
    {
    }

    // POST /posts — create post. Wire: validate body, insert, return 201.
    public function store(): void
    {
    }

    // PUT /posts/{id} — update post. Wire: authorize owner, update fields.
    public function update(): void
    {
    }

    // DELETE /posts/{id} — delete post. Wire: authorize owner, delete.
    public function destroy(): void
    {
    }
}
