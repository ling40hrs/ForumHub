# Cross-Layer Coordination Note

**Opened by:** `frontend | backend`
**Date:** `YYYY-MM-DD`
**Status:** `open | in-progress | resolved`

---

## Summary

One-line description of what feature/change needs the other layer.

---

## My Side (what I'm implementing)

```
Files touched:
- path/to/file1
- path/to/file2
```

Description of the changes on my side.

---

## Their Side (what the other dev needs to do)

### Backend work (if frontend is opening this)

| Endpoint | Method | Request body | Response shape |
|----------|--------|-------------|----------------|
| `/api/...` | GET/POST/PUT/DELETE | `{...}` | `{...}` |

### Frontend work (if backend is opening this)

| Route | Component | Data needed |
|-------|-----------|-------------|
| `/path` | `ComponentName` | `{...}` |

---

## Contract

Shared data shape between layers:

```json
{
  "id": 1,
  "field": "value"
}
```

---

## Notes

Any additional context, edge cases, or dependencies.
