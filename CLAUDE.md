# ForumHub — AI Context

A community-driven forum platform (Reddit-like) built with PHP 8 + React 18 + Tailwind CSS 3.

---

## 🔁 Centralized Workflow (MANDATORY)

Every AI agent **must** follow this workflow in order. No skipping phases.

### Phase 0 — Role Validation [FIRST]

**Before reading any file or writing any code, identify the user's role.**

> **Prompt**: "Are you working as Frontend Dev or Backend Dev today?"
>
> If the user doesn't specify, ask. If the request mentions files in both domains, block it and explain why. The role gates ALL subsequent work.

| Role | Allowed domains | Never touch |
|------|----------------|-------------|
| Frontend Dev | `frontend/`, `public/`, root docs (CLAUDE.md, README.md, etc.) | `api/`, `database/` |
| Backend Dev | `api/`, `database/`, root docs | `frontend/`, `public/` |

- Root config files (`CLAUDE.md`, `AGENTS.md`, `README.md`, `.gitignore`, etc.) are **shared** — either role can edit them.
- After role is set, write it to session memory so it persists across turns.

### Phase 1 — Scope Check

Map every file the user's request touches against the role's allowed domains.

- **All files in scope are owned by the user's role** → proceed.
- **Any file outside the user's domain** → **BLOCK** with message:

  > "⛔ This change touches `{path}`, which is owned by the {other} Dev. I can only edit files in {user_domain}. Please coordinate with the {other} Dev or file an issue."

- Cross-layer features (e.g. "add a comment feature" which needs both API and UI) — only implement your side. For the other side, generate a **cross-layer issue template** or a markdown note the other dev picks up.

### Phase 2 — Guardrails Check

Before writing code, verify the edit against these constraints:

| Guardrail | Check |
|-----------|-------|
| Layer purity | No mixing PHP + React in one file |
| File length | Target file ≤200 lines. If edit pushes over, split first. |
| Anti-patterns | Scan for `useEffect` data fetching, raw SQL, `axios`, skipped validation |
| Placeholder ban | Never write `// ... rest` or `/* existing code */` |
| Root cause | No `@ts-ignore`, no silencing — fix the real issue |

If any guardrail fails, **stop and fix before executing**.

### Phase 3 — Execute

- Prefer small, surgical edits over rewrites.
- Break >50-line changes into steps. State the plan before writing code.
- Run `ls` on the target directory before editing to confirm structure.
- Never edit a file you haven't read first.

### Phase 4 — Verify

After every change:
- **React**: `cd frontend && npm run build` (catches type/import errors)
- **PHP**: Run `php -l <file>` for syntax check
- **General**: Re-check file length doesn't exceed 200 lines

If verification fails, fix before reporting success.

---

## 👥 Team & Layer Ownership

| Member | Role | Owns | Excluded |
|--------|------|------|----------|
| @linglintz | Frontend Dev | `frontend/`, `public/` | `api/`, `database/` |
| [Backend Dev] | Backend Dev | `api/`, `database/` | `frontend/`, `public/` |

- No cross-layer edits without a GitHub issue coordinating the other side.
- Shared: `docs/`, root `.md` files, `.gitignore`, `.gitattributes`.

## 🧱 Tech Stack

| Layer | Technology | Key Config |
|-------|-----------|------------|
| Backend | PHP 8.x | `api/composer.json` |
| Frontend | React 18 | `frontend/vite.config.js` |
| Styling | Tailwind CSS 3 | `frontend/tailwind.config.js` |
| Database | MySQL 8 | `database/schema.sql` |
| Bundler | Vite 5 | — |

## 📁 Directory Reference

```
ForumHub/
├── api/              → PHP REST API (backend only)
│   ├── config/       → DB & app config
│   ├── controllers/  → Thin request handlers
│   ├── models/       → Business logic & PDO queries
│   ├── middleware/   → Auth, CORS, validation
│   ├── helpers/      → Response, validation utils
│   ├── public/       → index.php entry point
│   └── routes/       → Route definitions
├── frontend/         → React app (frontend only)
│   └── src/
│       ├── components/ → Reusable UI atoms
│       ├── features/   → Feature modules (auth, posts, …)
│       ├── hooks/      → Custom React hooks
│       ├── lib/        → API client (fetch wrapper)
│       ├── pages/      → Route/page components
│       ├── context/    → React Context providers
│       └── constants/  → Design tokens, config
├── database/         → SQL schema + migrations (backend)
├── public/           → Static assets (frontend)
└── docs/             → Wireframes, specs (shared)
```

## 📋 Conventions

### Code Style
- **React**: JSX, functional components, hooks, no classes. `const` > `function`.
- **PHP**: PSR-12. Every file starts with `declare(strict_types=1)`.
- **Tailwind**: Utility-first. No custom CSS unless impossible with utilities.
- **Imports**: No barrel files (`index.js`). Import paths directly.

### File Length
- **Hard ceiling**: 200 lines per file.
- **Target**: 100-150 lines. Split before 200.
- Controllers stay thin — push queries to models.

### React Guidelines
- No `useEffect` for fetching → use `usePosts`-style custom hooks.
- No `axios` → use `frontend/src/lib/fetch.js`.
- UI components render only. State/side effects in hooks.
- JSX > 60 lines → extract sub-components.

### PHP Guidelines
- No raw SQL in controllers → models own all queries.
- Validate + sanitize every input at the controller boundary.
- Controllers return JSON. No HTML.

### Git (Agent Rules)
- **Never run git commands unless the user explicitly asks.** No assumptions. No "obvious" commits or pushes.
- If the user asks for a git operation, only then: commit messages follow Conventional Commits format.
- Branch naming (when user asks): `feat/<name>`, `fix/<name>`, `refactor/<name>`, `docs/<name>`.

### Git (Project Conventions)
- Branches: `feat/<name>`, `fix/<name>`, `refactor/<name>`, `docs/<name>`.
- Commits: Conventional Commits (`feat:`, `fix:`, `refactor:`, `docs:`, `chore:`).
- PR into `main`. Each team member uses their own branches.

## 🚫 Anti-Patterns

- Raw SQL in controllers → use models.
- `useEffect` for data fetching → use custom hooks.
- Skipping PHP input validation → always sanitize.
- Barrel files → import directly.
- `axios` → use `frontend/src/lib/fetch.js`.
- Placeholders (`// ... rest`) → never.
- Files over 200 lines → split first.
- `@ts-ignore` → fix the type instead.
- Git commands by agent without user request → never. Ask first or wait for instruction.

## 🧪 Commands

```bash
# Backend (Backend Dev)
cd api && composer install
cd api && php -S localhost:8000 -t public

# Frontend (Frontend Dev)
cd frontend && npm install
cd frontend && npm run dev
cd frontend && npm run build
```

## 🔗 Key Files

| Purpose | Path |
|---------|------|
| AI agent instructions | `AGENTS.md` |
| Project proposal | `Context.md` |
| Database schema | `database/schema.sql` |
| API routes | `api/routes/api.php` |
| Frontend entry | `frontend/src/main.jsx` |
| Auth context | `frontend/src/context/AuthContext.jsx` |
| API client | `frontend/src/lib/fetch.js` |
| Centralized workflow | `CLAUDE.md` (this file) |
