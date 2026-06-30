# API Contract

Base URL: `http://localhost:8000/api`

All requests and responses use `Content-Type: application/json`.

Authentication: `Authorization: Bearer <token>` (JWT).

---

## Authentication

### POST /auth/register

Create a new account.

**Request:**
```json
{
  "username": "string (3-30 chars)",
  "email":    "string (valid email)",
  "password": "string (min 8 chars)"
}
```

**Response (201):**
```json
{
  "user": {
    "id": 1,
    "username": "johndoe",
    "email": "john@example.com",
    "avatar": null,
    "karma": 0,
    "created_at": "2026-06-30T00:00:00Z"
  },
  "token": "jwt.token.here"
}
```

**Errors:** 422 — validation failed

---

### POST /auth/login

Authenticate existing user.

**Request:**
```json
{
  "email":    "john@example.com",
  "password": "supersecret"
}
```

**Response (200):**
```json
{
  "user": {
    "id": 1,
    "username": "johndoe",
    "email": "john@example.com",
    "avatar": null,
    "karma": 0,
    "created_at": "2026-06-30T00:00:00Z"
  },
  "token": "jwt.token.here"
}
```

**Errors:** 401 — invalid credentials

---

## Communities

### GET /communities

List all communities.

**Response (200):**
```json
{
  "communities": [
    {
      "id": 1,
      "name": "Technology",
      "slug": "technology",
      "description": "All about tech",
      "member_count": 42,
      "created_at": "2026-06-30T00:00:00Z"
    }
  ]
}
```

### GET /communities/{id}

Get a single community with its posts.

**Response (200):**
```json
{
  "community": {
    "id": 1,
    "name": "Technology",
    "slug": "technology",
    "description": "All about tech",
    "owner_id": 1,
    "member_count": 42,
    "created_at": "2026-06-30T00:00:00Z"
  },
  "posts": []
}
```

**Errors:** 404 — community not found

---

## Posts

### GET /posts

List all posts (paginated).

**Query params:** `?page=1&limit=20&sort=hot|new|top&community_id=1`

**Response (200):**
```json
{
  "posts": [
    {
      "id": 1,
      "title": "Hello World",
      "body": "This is my first post!",
      "user_id": 1,
      "username": "johndoe",
      "community_id": 1,
      "community_slug": "technology",
      "score": 5,
      "comment_count": 3,
      "created_at": "2026-06-30T00:00:00Z"
    }
  ],
  "page": 1,
  "total": 50
}
```

### GET /posts/{id}

Get a single post with its comments.

**Response (200):**
```json
{
  "post": {
    "id": 1,
    "title": "Hello World",
    "body": "This is my first post!",
    "user_id": 1,
    "username": "johndoe",
    "community_id": 1,
    "community_slug": "technology",
    "score": 5,
    "comment_count": 3,
    "created_at": "2026-06-30T00:00:00Z"
  },
  "comments": []
}
```

### POST /posts

Create a new post (authenticated).

**Request:**
```json
{
  "title": "string (5-255 chars)",
  "body": "string",
  "community_id": 1
}
```

**Response (201):**
```json
{
  "post": {
    "id": 2,
    "title": "My New Post",
    "body": "Content here",
    "user_id": 1,
    "community_id": 1,
    "score": 0,
    "created_at": "2026-06-30T00:00:00Z"
  }
}
```

### PUT /posts/{id}

Update a post (owner only).

**Request:**
```json
{
  "title": "Updated title",
  "body": "Updated body"
}
```

**Response (200):**
```json
{
  "post": {
    "id": 1,
    "title": "Updated title",
    "body": "Updated body",
    "updated_at": "2026-06-30T01:00:00Z"
  }
}
```

### DELETE /posts/{id}

Delete a post (owner only).

**Response (204):** no body

---

## Comments

### POST /comments

Add a comment to a post (authenticated).

**Request:**
```json
{
  "post_id": 1,
  "parent_id": null,
  "body": "Great post!"
}
```

`parent_id` is `null` for top-level comments, or the parent comment's ID for replies.

**Response (201):**
```json
{
  "comment": {
    "id": 1,
    "body": "Great post!",
    "user_id": 1,
    "username": "johndoe",
    "post_id": 1,
    "parent_id": null,
    "score": 0,
    "created_at": "2026-06-30T00:00:00Z"
  }
}
```

---

## Votes

### POST /votes

Upvote or downvote a post or comment (authenticated).

**Request:**
```json
{
  "target_id": 1,
  "target_type": "post",
  "value": 1
}
```

`value`: `1` for upvote, `-1` for downvote, `0` to remove vote.

**Response (200):**
```json
{
  "target_id": 1,
  "target_type": "post",
  "score": 6,
  "user_vote": 1
}
```

---

## Users

### GET /users/{id}

Get a user's public profile.

**Response (200):**
```json
{
  "user": {
    "id": 1,
    "username": "johndoe",
    "avatar": null,
    "bio": "Forum enthusiast",
    "karma": 42,
    "created_at": "2026-06-30T00:00:00Z"
  }
}
```

### PUT /users/{id}

Update profile (authenticated user only).

**Request:**
```json
{
  "bio": "Updated bio",
  "avatar": "url-to-image.jpg"
}
```

**Response (200):**
```json
{
  "user": {
    "id": 1,
    "username": "johndoe",
    "avatar": "url-to-image.jpg",
    "bio": "Updated bio",
    "karma": 42
  }
}
```

---

## Health

### GET /

Server health check.

**Response (200):**
```json
{
  "status": "ok",
  "app": "ForumHub",
  "version": "0.1.0"
}
```
