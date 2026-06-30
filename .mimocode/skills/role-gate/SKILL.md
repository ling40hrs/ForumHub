---
name: role-gate
description: "Enforce layer ownership and role validation before editing code in the ForumHub project. Run this skill at the START of every session and before every code edit. Prevents cross-layer contamination between Frontend Dev and Backend Dev. Trigger: any code edit request, or when user says 'check role', 'validate', 'who am I'."
---

# Role Gate Skill

Enforce the 2-person team's layer ownership before touching any code.

## Phase 0 — Role Identification

At the start of every session or when a code edit is requested:

1. **Ask** the user: "Are you working as **Frontend Dev** or **Backend Dev** today?"
2. If the user has stated their role previously in this session, use that (don't re-ask).
3. If ambiguous, ask once — never guess.

## Phase 1 — Domain Validation

Check every file the request touches against the role's allowed domains:

| Role | Allowed | Forbidden |
|------|---------|-----------|
| **Frontend Dev** | `frontend/`, `public/`, root `.*` and `*.md` | `api/`, `database/` |
| **Backend Dev** | `api/`, `database/`, root `.*` and `*.md` | `frontend/`, `public/` |

### Validation rules:
- **All files in the user's domain** → proceed to execution.
- **Any file outside domain** → BLOCK. Print:
  ```
  ⛔ GUARDRAIL: {path} is owned by the {other_role}.
  You are working as {role}. I can only edit {allowed_domains}.
  ```
- **Mixed-domain request** (e.g., "add a comment feature" touching both `api/` and `frontend/`):
  - Implement only the side belonging to the current role.
  - Generate a cross-layer issue note for the other role to pick up.

## Phase 2 — Guardrail Checks

Before writing code, verify:
- File ≤ 200 lines (if edit would push over, split first)
- No mixing PHP + React in one file
- No raw SQL in controllers
- No `useEffect` for data fetching
- No `axios` — use `frontend/src/lib/fetch.js`
- No placeholders (`// ... rest`)
- No `@ts-ignore`

## Phase 3 — Cross-Layer Coordination

If a feature requires both layers:
1. **Implement your side only**.
2. **Generate a coordination note** in the following format and save it to `docs/coordination/`:

   ```markdown
   # Cross-Layer: {feature name}

   Requested by: {role}
   Date: {date}

   ## Backend work needed
   - {list of API endpoints / schema changes}

   ## Frontend work needed
   - {list of UI components / pages}

   ## Contract
   - Request/response shapes: {link to shared types if any}
   ```

## Output Format

When gate succeeds:
```
✅ Role: {role}
✅ Domain check: all files in scope
✅ Guardrails: passed
Proceeding with implementation...
```

When gate fails:
```
⛔ Role gate BLOCKED
Reason: {specific violation}
```
