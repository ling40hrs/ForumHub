# ForumHub

A community-driven forum web platform inspired by Reddit. Built with PHP, React, and Tailwind CSS.

**Team CodeCraft** — Web Design Course 2026

---

## Quick Start

```bash
git clone https://github.com/ling40hrs/ForumHub.git
cd ForumHub
bin/setup
```

See [`CONTRIBUTING.md`](./CONTRIBUTING.md) for full setup and workflow.

## Tech Stack

| Layer       | Technology    |
|-------------|---------------|
| Backend     | PHP 8.x       |
| Frontend    | React 18      |
| Styling     | Tailwind CSS 3 |
| Database    | MySQL         |
| Bundler     | Vite          |

## Team

| Role | GitHub |
|------|--------|
| Frontend Dev | [@ling40hrs](https://github.com/ling40hrs) |
| Backend Dev | [@Scarnile](https://github.com/Scarnile) |

## Project Structure

```
ForumHub/
├── api/                # PHP REST API (backend only)
├── frontend/           # React app (frontend only)
├── database/           # SQL schema + migrations
├── public/             # Static assets
├── docs/               # API contract, schemas, decisions, glossary
│   ├── api-contract.md # Endpoint specs
│   ├── schemas/        # JSON Schema (user, post, comment)
│   ├── decisions/      # Architecture Decision Records
│   ├── coordination/   # Cross-layer coordination notes
│   ├── handoff/        # AI session handoff notes
│   ├── mock/           # Mock API server for frontend dev
│   ├── environment.md  # Environment variable reference
│   └── glossary.md     # Domain terminology
├── .ai/                # AI session ritual + prompt library
│   ├── session-start.md
│   └── prompts/        # Reusable code generation templates
├── .github/            # Issue/PR templates, CI workflows
├── .vscode/            # Shared editor config + extensions
├── bin/                # Setup, seed, pre-commit scripts
├── docker-compose.yml  # PHP + MySQL containers
├── Makefile            # Command shortcuts
├── VERSION             # Single version number
├── CHANGELOG.md        # Release history
└── .editorconfig       # Cross-editor consistency
```

## Key Resources

| Resource | Path |
|----------|------|
| Onboarding guide | `CONTRIBUTING.md` |
| Centralized AI workflow | `CLAUDE.md` |
| AI agent instructions | `AGENTS.md` |
| API contract (shared spec) | `docs/api-contract.md` |
| Error codes (shared) | `api/config/errors.php` |
| Data schemas | `docs/schemas/` |
| Environment reference | `docs/environment.md` |
| Architecture decisions | `docs/decisions/` |
| Glossary | `docs/glossary.md` |
| Session start ritual | `.ai/session-start.md` |
| AI prompt library | `.ai/prompts/` |
| Cross-layer coordination | `.github/ISSUE_TEMPLATE/cross_layer_coordination.md` |
| Database schema | `database/schema.sql` |
| Change log | `CHANGELOG.md` |
| Mock API server | `docs/mock/server.js` |

## AI-Assisted Development

This repo is AI-centric — the centralized workflow in `CLAUDE.md` is the single source of truth for how every AI agent operates. Compatible with any AI coding tool (MiMoCode, Claude Code, Cursor, Copilot, etc.).

- **Session ritual** — every AI session starts with `.ai/session-start.md`: role confirmation, check pending coordination, check CI
- **Prompt library** — `.ai/prompts/` has reusable templates for generating endpoints, components, tests, and code reviews
- **Handoff notes** — AI writes `docs/handoff/` at session end so your teammate picks up without questions
