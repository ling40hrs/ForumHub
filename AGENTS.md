# ForumHub — AI Agent Instructions

This is a **PHP + React** full-stack forum project. **No Next.js.** See [`CLAUDE.md`](./CLAUDE.md) for full project context.

## 👥 Team (2 members)

| Member        | Owns                    | Never touch                   |
|---------------|-------------------------|-------------------------------|
| Frontend Dev  | `frontend/`, `public/`  | `api/`, `database/`           |
| Backend Dev   | `api/`, `database/`     | `frontend/`                   |

**Cross-layer changes need coordination** — if a feature requires both frontend and backend work, only edit your layer and leave a comment/issue for the other.

## 📜 Golden Rules

1. **Fix the Root** — no `@ts-ignore`, no silencing errors.
2. **JIT Context** — `ls` the target dir before editing.
3. **Small Diffs** — plan before writing >50 lines. Break big tasks into steps.
4. **Layer Awareness** — know PHP vs React. Never mix them in one file.
5. **Ask, Don't Guess** — ambiguous? Ask once. Don't write 100 lines of wrong code.

## 🚫 Anti-Patterns

- `useEffect` for data fetching → use a custom hook or React Query.
- `axios` → use `frontend/src/lib/fetch.js`.
- Raw SQL in PHP controllers → use models with PDO.
- Skipping PHP input validation → always sanitize.
- Mixing PHP + React in one file → keep separated.
- Placeholder code (`// ... rest`) → never.
- Files over 200 lines → split before editing.

## 🏗 Directory Layout

```
ForumHub/
├── api/              # PHP backend — your routes, controllers, models
├── frontend/         # React frontend — your components, pages, hooks
├── database/         # SQL schema + migrations
├── public/           # Static assets
└── docs/             # Specs + wireframes
```

## 📐 Conventions

- **File length**: ≤200 lines, target 100-150.
- **PHP**: PSR-12, `declare(strict_types=1)`, thin controllers.
- **React**: Functional components, custom hooks, no class components.
- **Git**: `feat/`, `fix/`, `refactor/` branches. Conventional Commits.
- **Commits**: `feat:`, `fix:`, `refactor:`, `docs:`, `chore:`.

## 💻 Commands

```bash
api/   → composer install, php -S localhost:8000 -t public
front/ → npm install, npm run dev, npm run build
```

## 🔁 Centralized Workflow

**Every AI agent MUST follow the workflow in [`CLAUDE.md`](./CLAUDE.md#-centralized-workflow-mandatory) — in order, no skipping.**

1. **Phase 0 — Role Validation** (ask before touching any code)
2. **Phase 1 — Scope Check** (validate files against user's domain)
3. **Phase 2 — Guardrails Check** (file length, anti-patterns, layer purity)
4. **Phase 3 — Execute** (small diffs, plan before >50 lines)
5. **Phase 4 — Verify** (build, syntax check, length check)

For complex multi-step tasks, run the workflow script:
```bash
workflow({ operation: "run", script: ".mimocode/workflows/forumhub-gated.js", args: { task: "...", role: "frontend|backend" } })
```

### Loadable Skills
- `role-gate` — enforce layer ownership before edits
- `quality-gate` — run verification checks after edits

See [`CLAUDE.md`](./CLAUDE.md) for the full reference.
