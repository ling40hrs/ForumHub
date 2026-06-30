---
name: Cross-layer coordination
about: Request work from the other layer (frontend needs backend, or vice versa)
labels: cross-layer
title: "[Coordination] <feature name>"
---

## Opened by

`frontend | backend`

## Summary

One-line description of the feature or change that needs the other layer.

---

## My side (already implemented or being implemented)

**Files:**
```
- path/to/my/files
```

**What I did:**
Brief description of the changes on my side.

---

## What I need from the other side

### If frontend needs backend

| Endpoint | Method | Request body | Response shape |
|----------|--------|-------------|----------------|
| `/api/...` | GET/POST/PUT/DELETE | `{...}` | `{...}` |

### If backend needs frontend

| Route | Component needed | Data to display |
|-------|-----------------|-----------------|
| `/page` | New page or component | `{...}` |

---

## Data contract

```json
{
  "shared_shape": "define exactly what passes between layers"
}
```

## Notes

Edge cases, dependencies, timing (e.g., "I need this before Sprint 3 demo").
