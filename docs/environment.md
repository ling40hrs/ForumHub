# Environment Variables

All environment variables used by ForumHub. Copy `api/.env.example` to `api/.env` and fill in.

## Application

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_ENV` | `development` | `development` or `production`. Controls error reporting verbosity. |
| `APP_DEBUG` | `true` | Show detailed error pages. Set `false` in production. |
| `APP_URL` | `http://localhost:8000` | Base URL of the API server. Used for CORS and link generation. |

## Database

| Variable | Default | Description |
|----------|---------|-------------|
| `DB_HOST` | `localhost` | MySQL server hostname. Use `127.0.0.1` for local TCP, or the Docker service name. |
| `DB_PORT` | `3306` | MySQL server port. |
| `DB_NAME` | `forumhub` | Database name. Created automatically by `database/schema.sql`. |
| `DB_USER` | `root` | MySQL user. Must have CREATE + CRUD permissions on `DB_NAME`. |
| `DB_PASS` | (empty) | MySQL user password. |

## Authentication

| Variable | Default | Description |
|----------|---------|-------------|
| `JWT_SECRET` | (none) | HMAC key for signing JWT tokens. **Change this in production.** Use a 64-char random string. |

## Example `.env`

```ini
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=forumhub
DB_USER=forumhub_user
DB_PASS=your_secure_password

JWT_SECRET=0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef
```
