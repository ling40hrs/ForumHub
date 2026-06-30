# ADR-001: Use PDO with Prepared Statements (no ORM)

**Date:** 2026-06-30
**Status:** Accepted

## Context

The backend needs a database access layer. Options:
- Raw `mysqli` — prone to SQL injection, verbose
- PDO with prepared statements — safe, portable, built into PHP
- Eloquent ORM (via Illuminate\Database) — heavy dependency, steep learning curve for a course project
- Doctrine — overkill for the scope

## Decision

Use **PDO with prepared statements** wrapped in model classes.

- `api/config/database.php` provides a singleton PDO connection
- Each model (`api/models/`) contains query methods
- No query builders or ORMs

## Consequences

- + No external dependency, ships with PHP
- + Full control over SQL queries
- + Easy to debug (exact SQL being executed)
- - Manual query writing (no auto-joins or relationship loading)
- - No migrations tool (schema changes managed via SQL files in `database/`)

## Alternatives considered

- Eloquent: too heavy for this project's scope, adds composer dependencies
- Raw mysqli: insecure without extreme discipline, PDO is strictly better
