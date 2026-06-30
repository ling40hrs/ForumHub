# ForumHub Glossary

Common domain terms used across both layers.

| Term | Definition | Used in |
|------|-----------|---------|
| **Community** | A topic-based group users can join and post in. Has a unique slug. | `communities` table, `/c/:slug` route |
| **Post** | A top-level submission within a community. Has title, body, author. | `posts` table, `/p/:id` route |
| **Comment** | A reply to a post or another comment. Supports nesting via `parent_id`. | `comments` table |
| **Vote** | Upvote (+1) or downvote (-1) on a post or comment. One per user per target. | `votes` table |
| **Score** | Net sum of upvotes and downvotes on a post or comment. | `posts.score`, `comments.score` |
| **Karma** | Total score across all user's posts and comments. | `users.karma` |
| **Flair** | Custom label next to a username in a community. Optional. | `community_members` |
| **Layer** | Either `frontend` (React) or `backend` (PHP). Never mixed in one file. | Code ownership |
| **Cross-layer** | A feature needing changes in both frontend and backend simultaneously. | Coordination issues |
