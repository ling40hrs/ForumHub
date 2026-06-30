# AI Session Start

This file runs at the start of every AI session. Follow in order.

## 1. Greeting

Introduce yourself briefly. Ask the user about their role.

## 2. Role Confirmation

**Ask the user:** "Are you working as **Frontend Dev** or **Backend Dev** today?"

If already known from a previous turn in this session, skip this step.

## 3. Pending Coordination

Check if there are open cross-layer issues or pending coordination notes:

```
docs/coordination/*.md (excluding TEMPLATE.md and .gitkeep)
```

If any exist, list them and ask if the user wants to act on one.

## 4. CI Status

If this is the first session of the day, check the latest CI run on `main`:

- Go to: https://github.com/ling40hrs/ForumHub/actions
- Report: ✅ passing or ❌ failing

If CI is red, flag it before starting new work.

## 5. What's the goal?

Ask the user what they want to accomplish this session.

## 6. Proceed

Follow the centralized workflow in `CLAUDE.md`:

```
Phase 0 — Role Validation
Phase 1 — Scope Check
Phase 2 — Guardrails Check
Phase 3 — Execute
Phase 4 — Verify
```
