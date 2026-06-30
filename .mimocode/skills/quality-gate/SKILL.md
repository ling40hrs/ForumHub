---
name: quality-gate
description: "Run the full quality gate cycle for the ForumHub project (PHP+React). After making code changes, verify syntax, build, and file length constraints. Trigger: user says 'run checks', 'verify', 'quality gate', 'make sure it builds', or after completing a feature/fix."
---

# Quality Gate Skill

Run the project quality checks after code changes. Stop at the first failure.

## Gate 1: File Length

Check every file you edited is ≤ 200 lines.

```bash
# Quick check
wc -l <file>
```

If any file exceeds 200 lines, split it before proceeding.

## Gate 2: PHP Syntax (Backend)

```bash
php -l api/controllers/*.php api/models/*.php api/middleware/*.php api/config/*.php api/helpers/*.php api/routes/*.php 2>&1
```

If errors, fix the syntax issue.

## Gate 3: React Build (Frontend)

```bash
cd frontend && npm run build 2>&1
```

If errors, fix imports, missing exports, or syntax issues.

## Gate 4: Verification Checklist

- [ ] No `@ts-ignore` or `eslint-disable` comments added
- [ ] No placeholder code (`// ... rest`)
- [ ] No raw SQL in controllers (PHP)
- [ ] No `useEffect` for data fetching (React)
- [ ] Input validation present on PHP endpoints
- [ ] `declare(strict_types=1)` on all PHP files

## Output Format

```
✅ File length: N files checked, all ≤200 lines
✅ PHP syntax: all files passed
✅ React build: successful
✅ Checklist: all items verified
```
