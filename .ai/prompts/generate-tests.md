# Prompt: Generate Tests

Use this prompt when AI should generate tests for backend or frontend code.

## Backend Tests (PHP)

```
Generate PHPUnit tests for [controller/model].

Test cases:
- Success path (valid input → expected output)
- Validation failure (invalid input → 422)
- Not found (non-existent id → 404)
- Unauthorized (no token → 401)
- Edge cases (empty list, pagination boundary)

Use PDO test fixtures in database/tests/ if needed.
```

## Frontend Tests (Vitest)

```
Generate Vitest tests for [component/hook].

Test cases:
- Renders successfully
- Loading state shows spinner/skeleton
- Error state shows error message
- Empty state shows empty message
- User interaction (click, submit) triggers correct behavior

Mock API calls from frontend/src/lib/fetch.js.
```
