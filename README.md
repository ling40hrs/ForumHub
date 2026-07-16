# Yapr

A Reddit-like forum built with PHP, MySQL, and Tailwind CSS.

## Quick Start

```bash
git clone https://github.com/ling40hrs/Yapr.git
cd Yapr

# Start dev server
npm run dev
```

Open http://localhost:8000. The PHP router serves pages from `api/` and static assets from `public/`.

## Tech Stack

| Layer       | Technology |
|-------------|------------|
| Backend     | PHP 8.x |
| Frontend    | PHP (server-rendered pages) |
| Styling     | Tailwind CSS 3 (CDN, no build step) |
| Database    | MySQL 8 |

## Team

| Role | GitHub |
|------|--------|
| Frontend Dev | [@ling40hrs](https://github.com/ling40hrs) |
| Backend Dev | [@Scarnile](https://github.com/Scarnile) |

## Project Structure

```
Yapr/
├── api/                # Server-rendered frontend pages
│   ├── index.php       # Home / feed
│   ├── login.php       # Login form
│   ├── register.php    # Registration form
│   ├── community.php   # Community view (?slug=)
│   ├── post.php        # Single post + comments (?id=)
│   ├── profile.php     # User profile (?id=)
│   └── includes/       # header, navbar, footer, helpers, db, auth
├── public/             # Static assets (css, fonts, images)
├── database/           # SQL schema
├── router.php          # Dev routing
├── package.json        # npm run dev shortcut
└── VERSION
```

## Key Resources

| Resource | Path |
|----------|------|
| Database schema | `database/schema.sql` |
| Dev server | `router.php` |
