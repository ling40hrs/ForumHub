<?php
// Static sample data for the frontend-only pass (no DB). Backend dev replaces with MySQL.

$currentUser = [
    'id' => 1, 'username' => 'ada', 'avatar_color' => '#ff4500', 'karma' => 1280,
    'bio' => 'CS student who builds things for the web. Moderator of r/webdev.',
];

$communities = [
    ['name' => 'Web Dev', 'slug' => 'webdev', 'description' => 'Talk about building for the web — HTML, CSS, PHP, JS.', 'members_count' => 4200],
    ['name' => 'PHP', 'slug' => 'php', 'description' => 'Everything PHP: frameworks, patterns, and gotchas.', 'members_count' => 3100],
    ['name' => 'Design', 'slug' => 'design', 'description' => 'UI/UX, typography, and visual design discussion.', 'members_count' => 2600],
    ['name' => 'MySQL', 'slug' => 'mysql', 'description' => 'Schemas, indexing, and query tuning.', 'members_count' => 1500],
    ['name' => 'Student Projects', 'slug' => 'student-projects', 'description' => 'Show off what you are building for class.', 'members_count' => 900],
];

$posts = [
    [
        'id' => 1, 'community' => 'r/webdev', 'community_slug' => 'webdev', 'author' => ['username' => 'ada'], 'author_id' => 1,
        'title' => 'I rebuilt my portfolio with plain PHP and Tailwind — here is what I learned',
        'body' => 'After a year of heavy frameworks I went back to basics. Server-rendered PHP pages plus a single compiled Tailwind stylesheet got me a fast, readable site with almost no tooling. The biggest win was deleting a whole build pipeline.',
        'score' => 142, 'comments_count' => 3, 'time' => '3h',
    ],
    [
        'id' => 2, 'community' => 'r/php', 'community_slug' => 'php', 'author' => ['username' => 'linus'], 'author_id' => 2,
        'title' => 'PDO parameter binding finally clicked for me',
        'body' => 'Prepared statements felt magic until I treated them as placeholders the DB fills in safely. No more string concatenation in queries.',
        'score' => 87, 'comments_count' => 2, 'time' => '7h',
    ],
    [
        'id' => 3, 'community' => 'r/design', 'community_slug' => 'design', 'author' => ['username' => 'mira'], 'author_id' => 3,
        'title' => 'A 4-color palette beats 40 utility classes',
        'body' => 'Constraining the theme to a small brand palette made every page feel coherent without arguing over hex codes.',
        'score' => 203, 'comments_count' => 1, 'time' => '1d',
    ],
];

$comments = [
    ['id' => 1, 'post_id' => 1, 'author' => ['username' => 'linus'], 'body' => 'The no-build-step life is underrated.', 'score' => 12, 'time' => '2h'],
    ['id' => 2, 'post_id' => 1, 'author' => ['username' => 'mira'], 'body' => 'Did you keep any JS at all?', 'score' => 4, 'time' => '1h'],
    ['id' => 3, 'post_id' => 1, 'author' => ['username' => 'ada'], 'body' => 'Just a tiny bit for the search box focus state.', 'score' => 6, 'time' => '1h'],
    ['id' => 4, 'post_id' => 2, 'author' => ['username' => 'ada'], 'body' => 'This is the way.', 'score' => 9, 'time' => '5h'],
    ['id' => 5, 'post_id' => 2, 'author' => ['username' => 'mira'], 'body' => 'Bookmarked. Thank you!', 'score' => 3, 'time' => '4h'],
    ['id' => 6, 'post_id' => 3, 'author' => ['username' => 'linus'], 'body' => 'Constraints breed consistency.', 'score' => 15, 'time' => '22h'],
];

$profileUser = $currentUser;
