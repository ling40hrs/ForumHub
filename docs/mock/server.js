// ForumHub Mock API Server
// Run: node docs/mock/server.js
// Provides fake data matching docs/api-contract.md for frontend development
// when the real backend isn't ready.

const http = require('http');

const data = {
  user: { id: 1, username: 'alice', email: 'alice@test.com', avatar: null, bio: 'Mock user', karma: 42, created_at: '2026-06-30T00:00:00Z' },
  posts: [
    { id: 1, title: 'Mock Post', body: 'This is mock data', user_id: 1, username: 'alice', community_id: 1, community_slug: 'technology', score: 5, comment_count: 2, created_at: '2026-06-30T00:00:00Z' },
    { id: 2, title: 'Second Post', body: 'More mock content', user_id: 2, username: 'bob', community_id: 2, community_slug: 'gaming', score: 3, comment_count: 0, created_at: '2026-06-30T01:00:00Z' },
  ],
  comments: [
    { id: 1, body: 'Great post!', user_id: 2, username: 'bob', post_id: 1, parent_id: null, score: 2, created_at: '2026-06-30T02:00:00Z' },
  ],
  communities: [
    { id: 1, name: 'Technology', slug: 'technology', description: 'All about tech', member_count: 42, created_at: '2026-06-30T00:00:00Z' },
    { id: 2, name: 'Gaming', slug: 'gaming', description: 'Video games', member_count: 17, created_at: '2026-06-30T00:00:00Z' },
  ],
};

function json(res, body, status = 200) {
  res.writeHead(status, { 'Content-Type': 'application/json', 'Access-Control-Allow-Origin': '*' });
  res.end(JSON.stringify(body));
}

const routes = {
  'GET /':          (_, res) => json(res, { status: 'ok', app: 'ForumHub Mock', version: '0.1.0' }),
  'GET /posts':     (_, res) => json(res, { posts: data.posts, page: 1, total: data.posts.length }),
  'GET /posts/1':   (_, res) => json(res, { post: data.posts[0], comments: data.comments.filter(c => c.post_id === 1) }),
  'GET /communities': (_, res) => json(res, { communities: data.communities }),
  'GET /communities/1': (_, res) => json(res, { community: data.communities[0], posts: data.posts.filter(p => p.community_id === 1) }),
  'GET /users/1':   (_, res) => json(res, { user: data.user }),
};

const server = http.createServer((req, res) => {
  const key = `${req.method} ${req.url.split('?')[0]}`;
  const handler = routes[key] || routes[`${req.method} /1`]?.bind(null, req, res);

  if (handler) {
    handler(req, res);
  } else {
    json(res, { error: 'Not found' }, 404);
  }
});

const PORT = 4000;
server.listen(PORT, () => {
  console.log(`ForumHub Mock API running at http://localhost:${PORT}`);
  console.log('Frontend: update vite.config.js proxy target to :4000 for mock mode.');
});
