---
name: closure-check
description: "Run a pre-session-closure audit for the ForumHub project. Verifies the session is safe to close: open tasks, build/lint status, handoff doc presence, scratchpad notes, uncommitted changes. Trigger: user says 'close the session', 'ready for closure', 'end of day', 'wrap up', 'done for today', or before manually ending a session."
---

# Closure Check Skill

Run a 5-step audit to confirm the session is safe to close and ready
for a future agent (or the user) to resume cleanly. This is an
**invoked** skill — run only when the user asks to close, not on
auto-trigger. Returns a ready/not-ready verdict and lists any gaps.

The skill is read-only: it inspects filesystem, runs build/lint, and
queries the task tool. It does **not** create handoff files, commit,
or modify state. If gaps are found, the skill tells the user what to
do; the user decides whether to fix now or carry over.

## When to invoke

- User says "close the session", "ready for closure", "end of day",
  "wrap up", "done for today", "I want to close", "ready to close",
  or similar.
- User asks "is everything documented and ready for handoff?"
- Before issuing a final message in a long session that involved
  code changes.

Do **not** auto-run on every session end. Session closure is a user
decision; the audit just confirms the state.

## Audit (run in order, stop at first hard fail)

### Step 1 — Open tasks

```
task({ operation: "list" })
```

- **Pass:** no tasks, OR all tasks are `done` / `cancelled`.
- **Fail:** any task in `open` / `in_progress` / `pending` / `blocked`.
  Report the task IDs and titles. Ask the user: "These tasks are
  still open. Mark them done, or leave them for the next session?"

### Step 2 — Build & lint status

For **frontend** changes:
```
cd frontend && npm run build 2>&1 | tail -10
cd frontend && npm run lint 2>&1 | tail -15
```

For **backend** changes (PHP):
```
cd api && composer validate --strict 2>&1
php -l <each modified PHP file>
```

For **docs-only** changes: skip build/lint (no code was touched).

- **Pass:** build exits 0; lint shows 0 errors (warnings are OK and
  consistent with the project's `react/prop-types` baseline).
- **Fail:** build error or lint error. Report the exact error. Do
  not auto-fix — ask the user.

If unsure whether changes were frontend vs backend, check the file
list from `git status` first.

### Step 3 — Handoff doc

Per `docs/handoff/README.md`, the convention is one handoff per
feature/session, named `YYYY-MM-DD--feature-name.md` in
`docs/handoff/`.

```
ls -t docs/handoff/*.md | head -5
```

- **Pass:** at least one handoff exists for the work done in this
  session, and its content follows the 4-section template:
  **what was done / what's left / blockers / what the other dev
  needs to know**.
- **Fail:** no handoff for today's session. Ask: "Write a handoff
  doc for today's work before closing?" If yes, draft it; if no,
  note the omission in the report and let the user decide.

If the session was docs-only or no code changed (e.g. a pure Q&A
session), a handoff is optional — say so and let the user decide.

### Step 4 — Notes scratchpad

```
cat /home/ling/.local/share/mimocode/memory/sessions/<sid>/notes.md
```

(Use the actual session id from the `session.metadata.session_id` or
the `checkpoints.active` path in context.)

- **Pass:** `notes.md` has at least one entry that references the
  current session's work (a handoff path, a task summary, or a
  decision).
- **Fail:** `notes.md` is empty or only has the template header.
  Append a one-paragraph entry summarizing the session's main
  outcome + the handoff path. Keep it short — the checkpoint
  writer will reconcile at the next checkpoint event.

### Step 5 — Uncommitted state

```
git status --short
```

- **Pass:** clean tree, OR the only changes are the ones the user
  has explicitly said to leave uncommitted (e.g. "keep working on
  it later").
- **Fail (warning, not blocker):** uncommitted modifications. List
  the modified + untracked files. The project rule is "AI agents
  must not run git commands unless the user explicitly asks" — so
  we report, we don't auto-commit. Note: this is a **state report,
  not a fail** — many users intentionally close with uncommitted
  work.

## Output format

After all 5 steps, return one of:

### Ready

```
✅ Session ready for closure
- Tasks: 0 open
- Build/lint: green (1932 modules, 0 errors, 7 prop-types warnings)
- Handoff: docs/handoff/2026-06-30--layout-shell.md ✓
- Notes: 2 entries
- Uncommitted: 5 modified + 2 new (intentional carry-over)

To resume tomorrow: cd frontend && npm run build && npm run dev
```

### Not ready

```
⛔ Session NOT ready for closure

Gaps:
- [ ] Tasks: T3 is in_progress (title: "Build data hooks")
- [ ] Handoff: no doc for today's work — write one?
- [ ] Notes: empty

Build/lint: green (verified, not the blocker)
Uncommitted: 5 modified + 2 new (carry-over OK)

Fix the gaps above, then re-run /closure-check.
```

A "not ready" result should also list what IS ready, so the user
knows they're not far off.

## Anti-patterns (do not do)

- **Do not auto-write the handoff.** Ask first. Handoffs are a
  user-facing artifact and the user should review the content.
- **Do not auto-commit.** Project rule: no git commands without
  explicit ask.
- **Do not auto-close tasks.** A user may want to leave a task
  open deliberately. Report, don't act.
- **Do not run this skill speculatively.** The user asked to close;
  this confirms the state, it doesn't decide.
- **Do not modify the project MEMORY.md** as part of this audit.
  Promotion of session-level discoveries is the checkpoint writer's
  job at the next event.

## See also

- `role-gate` — must run at session START, before any code edit
- `quality-gate` — must run AFTER code edits, before this skill
- The natural sequence is: `role-gate` → edit → `quality-gate` →
  edit → `quality-gate` → ... → `closure-check` → end
