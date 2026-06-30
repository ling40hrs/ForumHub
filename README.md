# ForumHub

A community-driven forum web platform inspired by Reddit. Built with PHP, React, and Tailwind CSS.

## Tech Stack

| Layer       | Technology    |
|-------------|---------------|
| Backend     | PHP 8.x       |
| Frontend    | React 18      |
| Styling     | Tailwind CSS 3 |
| Database    | MySQL         |
| Bundler     | Vite          |

## Getting Started

### Prerequisites

- PHP 8.1+
- Composer
- Node.js 18+
- MySQL 8+

### Backend Setup

```bash
cd api
composer install
cp .env.example .env   # configure database credentials
php -S localhost:8000 -t public
```

### Frontend Setup

```bash
cd frontend
npm install
npm run dev
```

### Database

```bash
mysql -u root -p < database/schema.sql
```

## Project Structure

```
ForumHub/
├── api/              # PHP REST API
│   ├── controllers/  # Request handlers
│   ├── models/       # Business logic & DB queries
│   ├── middleware/    # Auth, CORS, validation
│   └── config/       # App & DB configuration
├── frontend/         # React application
│   └── src/
│       ├── components/   # Reusable UI components
│       ├── features/     # Feature modules
│       ├── hooks/        # Custom React hooks
│       ├── lib/          # API client & utilities
│       ├── pages/        # Route pages
│       └── context/      # React Context providers
├── database/         # Schema & migrations
└── public/           # Static assets
```

## Team

| Role | GitHub |
|------|--------|
| Frontend Dev | [@ling40hrs](https://github.com/ling40hrs) |
| Backend Dev | [@Scarnile](https://github.com/Scarnile) |

**Team CodeCraft** — Web Design Course 2026

## AI-Assisted Development

This repo is configured for AI-centric development with MiMoCode/OpenCode.

- `CLAUDE.md` — primary AI context file (project conventions, team boundaries, layering)
- `AGENTS.md` — quick-reference AI agent instructions
- See `CLAUDE.md` for full development conventions and workflow.
