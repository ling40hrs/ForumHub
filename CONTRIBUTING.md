# Contributing to ForumHub

Welcome! This guide tells you everything you need to work on ForumHub with your teammate.

## Team Structure

| Role | GitHub | Owns | Never touch |
|------|--------|------|-------------|
| Frontend Dev | [@ling40hrs](https://github.com/ling40hrs) | `frontend/`, `public/` | `api/`, `database/` |
| Backend Dev | [@Scarnile](https://github.com/Scarnile) | `api/`, `database/` | `frontend/`, `public/` |

Root files (`CLAUDE.md`, `AGENTS.md`, `README.md`, `docs/`, `.github/`) are shared.

**Golden rule**: if a change touches files in both layers, you only implement your side. File a cross-layer coordination issue for the other side — never implement both.

## Quick Start

### Prerequisites

- PHP 8.1+, Composer
- Node.js 18+, npm
- MySQL 8+

### One-command setup

```bash
bin/setup
```

This runs `composer install` + `npm install` + copies `.env.example` → `.env`.

### Manual setup

```bash
# Backend
cd api && composer install
cp .env.example .env    # edit DB credentials
php -S localhost:8000 -t public

# Frontend (separate terminal)
cd frontend && npm install
npm run dev              # starts on :3000, proxies /api to :8000

# Database
mysql -u root -p < database/schema.sql
```

## Centralized Workflow (Mandatory)

Every AI agent or human contributor follows this flow **in order**:

```
Phase 0 — Role Validation     → I am Frontend or Backend?
Phase 1 — Scope Check         → Do all touched files belong to me?
Phase 2 — Guardrails Check    → File length, anti-patterns, layer purity?
Phase 3 — Execute             → Plan, small diffs, no placeholders
Phase 4 — Verify              → Build, lint, syntax check passes?
```

See [`CLAUDE.md`](./CLAUDE.md#-centralized-workflow-mandatory) for the full reference.

## Key Resources

| Resource | Path | Purpose |
|----------|------|---------|
| API contract | `docs/api-contract.md` | All endpoints, request/response shapes |
| Error codes | `api/config/errors.php` | Shared error codes between layers |
| Data schemas | `docs/schemas/` | JSON Schema for user, post, comment |
| Glossary | `docs/glossary.md` | Domain terminology |
| Environment reference | `docs/environment.md` | All env vars explained |
| Architecture decisions | `docs/decisions/` | ADR log (why we chose what) |
| Changelog | `CHANGELOG.md` | Per-release changes |
| AI prompts | `.ai/prompts/` | Reusable prompt templates for code generation |
| Session ritual | `.ai/session-start.md` | What AI does at session start |
| Handoff notes | `docs/handoff/` | Session handoff for teammate |
| VS Code config | `.vscode/` | Shared editor settings + extensions |

## AI-Assisted Development

This repo works with any AI coding tool (MiMoCode, Claude Code, Cursor, Copilot, etc.). The workflow lives in `CLAUDE.md` — every AI tool reads this file on startup.

- **AI agents** automatically follow the 5-phase workflow
- **Skills** in `.mimocode/skills/` can be loaded for role-gating and quality checks
- **No git operations by AI** unless you explicitly ask

## Cross-Layer Coordination

If your feature needs the other layer's work:

1. **Implement your side only**
2. **Open a cross-layer coordination issue** using the template
3. The other dev picks it up and implements their side

The coordination issue must specify:
- Endpoint path, method, request/response shapes (when backend work is needed)
- Component requirements, route paths (when frontend work is needed)

## Git Conventions

- **No AI agent runs git commands unless you explicitly ask**
- Branches: `feat/<name>`, `fix/<name>`, `refactor/<name>`, `docs/<name>`
- Commits: Conventional Commits (`feat:`, `fix:`, `refactor:`, `docs:`, `chore:`)
- PR into `main`. Always include the layer in the PR description.

## Tools

### Makefile

Common commands:

```bash
make setup        # one-time setup
make dev-back     # start PHP dev server on :8000
make dev-front    # start Vite dev server on :3000
make lint         # run both PHPCS + ESLint
make build        # build frontend
make check        # lint + build
make seed         # seed database with test data
```

### Docker (alternative to manual setup)

```bash
docker compose up -d
```

Starts PHP on `:8000` and MySQL on `:3306` with the schema auto-loaded.

### Mock API server (frontend dev)

When the real backend isn't ready:

```bash
node docs/mock/server.js
# Mock API at http://localhost:4000
```

Then update `frontend/vite.config.js` proxy target to `http://localhost:4000` for mock mode.

### Pre-commit hook

```bash
bin/pre-commit
```

Or install it so it runs automatically:
```bash
ln -sf ../../bin/pre-commit .git/hooks/pre-commit
```

Checks: file length ≤200, PHP syntax, no `@ts-ignore`.

### Seed data

```bash
make seed
# or
bin/seed
```

Inserts test users (alice, bob, charlie), communities (technology, gaming, design), posts, and comments.

## Code Quality

Before committing:

```bash
# Backend — lint
cd api && ./vendor/bin/phpcs --standard=phpcs.xml src/

# Frontend — build check
cd frontend && npm run build

# Frontend — lint
cd frontend && npm run lint
```

### File length
- Hard ceiling: **200 lines** per file. Target: 100-150.
- Split before 200, not after.

### PHP
- PSR-12, `declare(strict_types=1)` on every file
- No raw SQL in controllers — use models
- Validate every input, return JSON

### React
- Functional components + hooks, no classes
- No `useEffect` for data fetching — use custom hooks
- No `axios` — use `frontend/src/lib/fetch.js`
- JSX over 60 lines → extract sub-components

## Issue & PR Conventions

- **Bug reports**: use the bug report template
- **Feature requests**: use the feature request template
- **Cross-layer work**: use the cross-layer coordination template
- **PRs**: use the PR template — check every box before merging
