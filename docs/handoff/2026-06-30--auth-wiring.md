# Handoff: Frontend Auth Wiring

**Date:** 2026-06-30
**Session:** ses_0e6e565daffeuZHoIytNSqM8aQ
**Role:** Frontend Dev

## What was done

Wired JWT auth into the React frontend against the API contract
(`docs/api-contract.md`, auth section). The frontend now has a working
auth foundation: token is persisted, attached to every fetch, and
sessions are auto-cleared on a 401 from the API.

### Files changed (3, all in `frontend/src/`)

| File | Status | Purpose |
|------|--------|---------|
| `constants/auth.js` | NEW (3 lines) | Shared `TOKEN_KEY`, `USER_KEY`, `UNAUTH_EVENT` constants. Lets `lib/fetch.js` and `context/AuthContext.jsx` agree on storage keys and the 401 event name without a circular import. |
| `lib/fetch.js` | Rewritten (52 lines) | Now attaches `Authorization: Bearer <token>` from localStorage to every request, exports a typed `ApiError` class (with `.status` + `.body`), and on 401 clears the token and emits the `forumhub:unauthorized` window event. |
| `context/AuthContext.jsx` | Rewritten (55 lines) | Now: rehydrates `user` from localStorage on mount, exposes `register()` in addition to `login()`, subscribes to the 401 event to auto-clear user state, and removes the token on `logout()`. |

### Architectural decisions

These follow patterns already documented in `MEMORY.md` → "Discovered
durable knowledge":

- **JWT storage = localStorage** — `TOKEN_KEY = 'forumhub-token'`.
  Persists across reloads. The XSS tradeoff is acknowledged in MEMORY
  (httpOnly cookies would need backend support that isn't in the API
  contract).
- **Circular-import avoidance** — `lib/fetch.js` reads the token
  directly from localStorage; it does NOT import `AuthContext`. On a
  401 it dispatches a `window` `CustomEvent('forumhub:unauthorized')`
  and `AuthContext` subscribes. This keeps the data layer free of UI
  dependencies.
- **No new dependencies added** — pure vanilla React 18 + `fetch`.
  No axios, no React Query, no auth library.
- **No `useEffect` for data fetching** — the `useEffect` in
  `AuthProvider` is event subscription only, not data fetching.
  Login/register call `fetch` inside event handlers (which is fine).

### Verification

```
$ cd frontend && npm run build
✓ 43 modules transformed.
✓ built in 2.71s
JS 162.05 kB │ gzip: 52.62 kB

$ cd frontend && npm run lint
✖ 1 problem (0 errors, 1 warning)
  13:32  warning  'children' is missing in props validation  react/prop-types
```

The remaining prop-types warning is the same pattern the rest of the
project uses (the scaffold sets `react/prop-types` to `warn`); not
introduced by this work.

File-length audit: 3 / 55 / 52 lines — well under the 200-line ceiling.

## What's left to do

Next concrete pieces (in dependency order):

1. **`Layout` component + navbar** (frontend/src/components/) — gives
   pages a shell with login/logout controls. Required before any page
   feels "real."
2. **Data hooks** in `frontend/src/hooks/` — `usePosts`, `useCommunity`,
   `usePost`, `useComments`, `useUser`. Required by the no-`useEffect`-
   for-fetching rule, and a precondition for steps 3-5.
3. **`LoginPage` + `RegisterPage`** — real forms calling `login()` /
   `register()` from `useAuth()`.
4. **`HomePage` post list** — `GET /posts` with sort tabs (hot/new/top)
   and pagination.
5. **`CommunityPage`**, **`PostPage`**, **`ProfilePage`** — fill in the
   remaining skeletons against the contract.
6. **Vote button + create/edit forms** — mutations on the existing
   detail pages.

## Blockers / decisions

- **Mock server has no auth routes** (`docs/mock/server.js` only
  implements `GET /posts`, `GET /posts/1`, `GET /communities`,
  `GET /communities/1`, `GET /users/1`, `GET /`). The login/register
  flows can't be tested end-to-end against the mock. Options:
  (a) extend `docs/mock/server.js` to add `POST /auth/login` +
  `POST /auth/register` returning a fake token; (b) wait for the PHP
  backend. Coordination note would help if (a) is chosen — touching
  `docs/mock/server.js` is fine for Frontend Dev, but the backend dev
  should know the contract we're working against.
- **Vite proxy target** is `http://localhost:8000` (real backend). To
  point at the mock during frontend-only development, change to
  `http://localhost:4000` and add a `rewrite` rule to strip the
  `/api` prefix (mock routes are `GET /posts`, no prefix). See MEMORY
  → "Vite proxy does NOT strip the path prefix by default" for the
  exact config.

## What the backend dev needs to know

- Frontend now sends `Authorization: Bearer <token>` on every request
  that needs it. Backend's `Authorization: Bearer <token>` line in
  `docs/api-contract.md` is being honored.
- Token is opaque to the frontend — frontend only stores and forwards
  it. HMAC signing/validation is entirely backend's concern
  (`JWT_SECRET` in `docs/environment.md`).
- `401` from any endpoint is treated as "token expired or invalid" —
  frontend clears the session and dispatches a re-login prompt. If
  the backend uses other status codes for auth failure (e.g. 403),
  let me know and I'll adjust `lib/fetch.js`.
