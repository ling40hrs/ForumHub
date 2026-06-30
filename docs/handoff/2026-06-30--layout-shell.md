# Handoff: Frontend Layout Shell + Dev User Switcher

**Date:** 2026-06-30
**Session:** ses_0e6e565daffeuZHoIytNSqM8aQ
**Role:** Frontend Dev

## What was done

Built the page chrome that wraps every route: a navbar with auth-aware
right-hand area, a user menu when logged in, and a dev-only fake-user
switcher. Wired the layout into `App.jsx` so all 6 existing pages now
render with the shell. Extended `AuthContext` to react to a new
`forumhub:auth-change` event so the dev toggle (and any future
cross-tab login change) re-hydrates React state from localStorage.

### Files changed (7, all in `frontend/src/`)

| File | Status | Purpose |
|------|--------|---------|
| `components/Layout.jsx` | NEW (10 lines) | Page chrome: `<Navbar/>` + max-width `<main>{children}</main>`. |
| `components/Navbar.jsx` | NEW (36 lines) | Brand link left, auth-aware right side: `Sign in`/`Sign up` links when logged out, `<UserMenu/>` when logged in, `<DevUserSwitcher/>` always (in dev). |
| `components/UserMenu.jsx` | NEW (24 lines) | Logged-in indicator: username (with karma) linking to `/u/:id` + Logout button. |
| `components/DevUserSwitcher.jsx` | NEW (63 lines) | Dev-only fake-user picker. Amber "Dev" button → dropdown of alice/bob + Logout. Tree-shaken from production via `import.meta.env.DEV`. |
| `constants/auth.js` | +1 line (4 total) | Added `AUTH_CHANGE_EVENT = 'forumhub:auth-change'`. |
| `context/AuthContext.jsx` | +1 listener (60 total) | Subscribes to `AUTH_CHANGE_EVENT` and re-reads localStorage when it fires. `UNAUTH_EVENT` listener unchanged. |
| `App.jsx` | +2 lines (26 total) | Wraps `<Routes>` in `<Layout>`. |

### Architectural decisions

- **Layout chrome is one `Layout` + a `Navbar`** — no `AppLayout`,
  `Sidebar`, `RequireAuth` etc. yet. MEMORY.md says the prior session
  split these; I'm rebuilding incrementally and will split `Navbar`
  into sub-components if it grows past ~100 lines.
- **Auth state propagation via CustomEvent, not a shared context
  call** — `DevUserSwitcher` writes to `localStorage` and dispatches
  `forumhub:auth-change`. `AuthContext` re-reads. Keeps the dev tool
  ignorant of `AuthContext` (and vice versa). Same pattern as
  `forumhub:unauthorized` from #1.
- **Dev-only component uses `import.meta.env.DEV` + early return** —
  Vite statically replaces this with `false` in production and DCE
  strips the component body. Verified: `alice`/`bob`/`dev-fake-token`/
  `FAKE_USERS` are absent from `dist/assets/index-*.js`. The two
  `forumhub-*` storage keys remain (they're imported by the always-
  present `AuthContext`).
- **Dev switcher = thin wrapper + inner component** — the `DEV` check
  is in the outer function (returns `null` in prod), the inner
  function owns all hooks. This avoids the `react-hooks/rules-of-hooks`
  error you get from putting `if (!DEV) return null` before a
  `useState`.
- **No `useEffect` for data fetching** — none added. The only
  `useEffect` remains the auth-event subscription in `AuthContext`,
  which is event-listener setup, not data fetching.
- **No new dependencies** — pure React 18 + `react-router-dom` `Link`.

### Verification

```
$ cd frontend && npm run build
✓ 47 modules transformed.
✓ built in 1.89s
JS 168.67 kB │ gzip: 54.77 kB
CSS 8.19 kB  │ gzip: 2.20 kB
(+6.62 kB JS / +2.15 kB gzip vs #1, mostly the new components + Navbar)

$ cd frontend && npm run lint
✖ 7 problems (0 errors, 7 warnings)
  all 'react/prop-types' — same baseline pattern as the rest of the project
```

File-length audit: 10 / 36 / 24 / 63 / 4 / 60 / 26 lines — all well
under the 200-line ceiling (largest is `DevUserSwitcher` at 63).

### Dev switcher behavior

In dev mode, the navbar shows a small amber "Dev" button to the right
of the auth area. Clicking it opens a dropdown with:

- **alice (karma 42)** — sets `localStorage` user to alice, token to
  `dev-fake-token`, dispatches `forumhub:auth-change`. UI flips to
  `<UserMenu/>` immediately.
- **bob (karma 12)** — same, with bob.
- **Log out** — clears both, UI flips back to `Sign in` / `Sign up`.

The active user is annotated "(active)" in the dropdown. The fake
token will be rejected by any real backend, so any actual API call
(POST/PUT/DELETE) will 401 and trigger the existing `UNAUTH_EVENT` →
auto-logout. Predictable.

## What's left to do

- **#3 — Data hooks** (`usePosts`, `useCommunity`, `usePost`,
  `useComments`, `useUser`) in `frontend/src/hooks/`. Required before
  HomePage/CommunityPage/PostPage/ProfilePage can fetch without
  `useEffect` (project rule).
- **#4 — Login + Register forms** on the existing `LoginPage` /
  `RegisterPage` skeletons. Will use `useAuth().login()` /
  `useAuth().register()`.
- **#5 — HomePage post list** — needs `usePosts` first.
- **#6 — CommunityPage / PostPage / ProfilePage** content.

## Blockers / decisions

- **Vite proxy still points at `:8000`** (real backend). For frontend-
  only development against the mock server, retarget to `:4000` and
  add a `rewrite` rule to strip `/api` (see MEMORY → "Vite proxy does
  NOT strip the path prefix by default"). Not blocking this work — the
  layout shell renders without any API calls.
- **Sidebar (community list) intentionally not built** — needs
  `useCommunities` from #3. Adding a placeholder now would be a
  drive-by refactor.
- **Theme toggle intentionally not built** — separate concern, not
  requested.
- **Mobile hamburger intentionally not built** — desktop-first; add
  when responsive design becomes a priority.

## What the other dev needs to know

- Frontend now wraps every route in `<Layout>`. The navbar emits no
  API calls. The dev switcher is purely local (writes to
  `localStorage` and fires an in-memory event) — no network.
- `AuthContext` now listens to **two** events on `window`:
  `forumhub:unauthorized` (existing, from `lib/fetch.js` 401 path)
  and `forumhub:auth-change` (new, from dev tool and any future
  cross-tab login change). Both clear/re-read localStorage; neither
  calls the backend.
