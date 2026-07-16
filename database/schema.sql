-- Yapr Database Schema

CREATE DATABASE IF NOT EXISTS yapr
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE yapr;

-- ── Users ──────────────────────────────────────────────
CREATE TABLE users (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(30)  NOT NULL UNIQUE,
    email      VARCHAR(255) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    avatar     VARCHAR(255) DEFAULT NULL,
    bio        TEXT         DEFAULT NULL,
    karma      INT          NOT NULL DEFAULT 0,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ── Communities ────────────────────────────────────────
CREATE TABLE communities (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(50)  NOT NULL,
    slug        VARCHAR(60)  NOT NULL UNIQUE,
    description TEXT         DEFAULT NULL,
    owner_id    INT UNSIGNED NOT NULL,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Community Members ─────────────────────────────────
CREATE TABLE community_members (
    user_id      INT UNSIGNED NOT NULL,
    community_id INT UNSIGNED NOT NULL,
    joined_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, community_id),
    FOREIGN KEY (user_id)      REFERENCES users(id)       ON DELETE CASCADE,
    FOREIGN KEY (community_id) REFERENCES communities(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Posts ──────────────────────────────────────────────
CREATE TABLE posts (
    id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(255) NOT NULL,
    body         TEXT         NOT NULL,
    user_id      INT UNSIGNED NOT NULL,
    community_id INT UNSIGNED NOT NULL,
    score        INT          NOT NULL DEFAULT 0,
    created_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)      REFERENCES users(id)       ON DELETE CASCADE,
    FOREIGN KEY (community_id) REFERENCES communities(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Comments ──────────────────────────────────────────
CREATE TABLE comments (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    body       TEXT         NOT NULL,
    user_id    INT UNSIGNED NOT NULL,
    post_id    INT UNSIGNED NOT NULL,
    parent_id  INT UNSIGNED DEFAULT NULL,
    score      INT          NOT NULL DEFAULT 0,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)   REFERENCES users(id)   ON DELETE CASCADE,
    FOREIGN KEY (post_id)   REFERENCES posts(id)   ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Votes ──────────────────────────────────────────────
CREATE TABLE votes (
    user_id   INT UNSIGNED NOT NULL,
    target_id INT UNSIGNED NOT NULL,
    target_type ENUM('post', 'comment') NOT NULL,
    value     TINYINT NOT NULL, -- +1 upvote, -1 downvote
    PRIMARY KEY (user_id, target_id, target_type),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ── Sample Data ─────────────────────────────────────────
-- All sample passwords are the bcrypt hash of 'a' for demo purposes.

INSERT INTO users (id, username, email, password, bio, karma) VALUES
(1, 'ada', 'ada@yapr.dev', '$2y$10$eHDr2RHfhDoUy4z77rFPOueQb5aUDNDegOtpE8efbqQhE5pOGHVG2', 'CS student who builds things for the web. Moderator of r/webdev.', 1280),
(2, 'linus', 'linus@yapr.dev', '$2y$10$eHDr2RHfhDoUy4z77rFPOueQb5aUDNDegOtpE8efbqQhE5pOGHVG2', 'PHP enthusiast and open-source contributor.', 960),
(3, 'mira', 'mira@yapr.dev', '$2y$10$eHDr2RHfhDoUy4z77rFPOueQb5aUDNDegOtpE8efbqQhE5pOGHVG2', 'UI/UX designer who codes. Loves typography and dark mode.', 2040),
(4, 'john', 'john@yapr.dev', '$2y$10$eHDr2RHfhDoUy4z77rFPOueQb5aUDNDegOtpE8efbqQhE5pOGHVG2', 'Learning web development and building cool stuff.', 150),
(5, 'jane', 'jane@yapr.dev', '$2y$10$eHDr2RHfhDoUy4z77rFPOueQb5aUDNDegOtpE8efbqQhE5pOGHVG2', 'Full-stack developer. Ask me about databases.', 720);

INSERT INTO communities (id, name, slug, description, owner_id) VALUES
(1, 'Web Dev', 'webdev', 'Talk about building for the web — HTML, CSS, PHP, JS.', 1),
(2, 'PHP', 'php', 'Everything PHP: frameworks, patterns, and gotchas.', 1),
(3, 'Design', 'design', 'UI/UX, typography, and visual design discussion.', 2),
(4, 'MySQL', 'mysql', 'Schemas, indexing, and query tuning.', 2),
(5, 'Student Projects', 'student-projects', 'Show off what you are building for class.', 3);

INSERT INTO community_members (user_id, community_id) VALUES
(1,1),(2,1),(3,1),(4,1),(5,1),
(1,2),(2,2),(3,2),(5,2),
(1,3),(2,3),(3,3),
(1,4),(4,4),(5,4),
(1,5),(3,5),(4,5),(5,5);

INSERT INTO posts (id, title, body, user_id, community_id, score, created_at) VALUES
(1, 'I rebuilt my portfolio with plain PHP and Tailwind — here is what I learned',
    'After a year of heavy frameworks I went back to basics. Server-rendered PHP pages plus Tailwind got me a fast, readable site with almost no tooling.', 1, 1, 142, NOW() - INTERVAL 3 HOUR),
(2, 'PDO parameter binding finally clicked for me',
    'Prepared statements felt magic until I treated them as placeholders the DB fills in safely. No more string concatenation in queries.', 2, 2, 87, NOW() - INTERVAL 7 HOUR),
(3, 'A 4-color palette beats 40 utility classes',
    'Constraining the theme to a small brand palette made every page feel coherent without arguing over hex codes.', 3, 3, 203, NOW() - INTERVAL 1 DAY);

INSERT INTO comments (id, body, user_id, post_id, score, created_at) VALUES
(1, 'The no-build-step life is underrated.', 2, 1, 12, NOW() - INTERVAL 2 HOUR),
(2, 'Did you keep any JS at all?', 3, 1, 4, NOW() - INTERVAL 1 HOUR),
(3, 'Just a tiny bit for the search box focus state.', 1, 1, 6, NOW() - INTERVAL 1 HOUR),
(4, 'This is the way.', 1, 2, 9, NOW() - INTERVAL 5 HOUR),
(5, 'Bookmarked. Thank you!', 3, 2, 3, NOW() - INTERVAL 4 HOUR),
(6, 'Constraints breed consistency.', 2, 3, 15, NOW() - INTERVAL 22 HOUR);

INSERT INTO votes (user_id, target_id, target_type, value) VALUES
(2,1,'post',1),(3,1,'post',1),
(2,3,'post',1),(3,3,'post',1),
(1,3,'post',-1),
(3,4,'comment',1),(1,6,'comment',1);

-- ── Indexes for performance (Week 8) ─────────────────
CREATE INDEX idx_posts_community_id ON posts(community_id);
CREATE INDEX idx_posts_created_at ON posts(created_at);
CREATE INDEX idx_comments_post_id ON comments(post_id);
CREATE INDEX idx_communities_slug ON communities(slug);

-- ── Additional Sample Data for Demo ─────────────────────

INSERT INTO posts (id, title, body, user_id, community_id, score, created_at) VALUES
(4, 'Best way to center a div in 2026?',
    'I know this is a meme at this point, but with all the new CSS features, what''s the actual best way now? Container queries? Grid? Flexbox? Old-school margin auto?',
    4, 1, 45, NOW() - INTERVAL 2 DAY),
(5, 'I built a dark mode toggle with zero JavaScript',
    'Pure CSS using the :has() selector and a checkbox. No JS at all. Here''s how I did it... actually it was surprisingly simple. Just one input and some sibling selectors.',
    1, 1, 78, NOW() - INTERVAL 4 DAY),
(6, 'Why I stopped using JavaScript frameworks for landing pages',
    'Server-rendered HTML loads faster, works without JS, and ranks better on SEO. For content-heavy sites, plain HTML+CSS is often the better choice. Change my mind.',
    2, 1, 112, NOW() - INTERVAL 5 DAY),
(7, 'PHP still runs 77% of the web — let that sink in',
    'Everybody wants to hate on PHP but WordPress, Facebook (originally), and Wikipedia all run on it. The language has evolved a LOT since PHP 7. Match expressions? Enums? Fibers?',
    2, 2, 67, NOW() - INTERVAL 1 DAY),
(8, 'isset() vs empty() vs is_null() — when to use what',
    'I used to just throw isset() everywhere until I actually read the docs. empty() returns true for empty string and 0. is_null() only for actual null. isset() returns false if the variable doesn''t even exist. Each has its place.',
    5, 2, 34, NOW() - INTERVAL 3 DAY),
(9, 'PDO vs MySQLi — which one should students learn?',
    'For a school project, MySQLi is simpler. Fewer lines, less boilerplate. PDO is more professional but overkill for a basic CRUD app. Learn MySQLi first, then PDO when you need multiple database drivers.',
    1, 2, 55, NOW() - INTERVAL 6 DAY),
(10, 'I redesigned my portfolio using only 3 colors',
    'Constraints breed creativity. I limited myself to black, white, and one accent color. Every component had to work within that palette. Best design decision I ever made — everything looks cohesive.',
    3, 3, 156, NOW() - INTERVAL 12 HOUR),
(11, 'Figma to code: my workflow for pixel-perfect conversion',
    'Step 1: Measure spacing in Figma. Step 2: Write CSS custom properties for every value. Step 3: Build components from the ground up. No shortcuts. Takes longer but the result is actually pixel-perfect.',
    3, 3, 89, NOW() - INTERVAL 2 DAY),
(12, 'Typography tips for non-designers',
    'Use 1.5 line-height for body text. Limit your font stack to 2 families. Hierarchy is more important than fancy fonts. If it''s hard to read, it doesn''t matter how pretty it looks.',
    3, 3, 203, NOW() - INTERVAL 3 DAY),
(13, 'Why your JOIN queries are slow — and how to fix them',
    'If your JOIN takes seconds, check three things: (1) do you have indexes on the foreign keys? (2) are you selecting more columns than you need? (3) is your EXPLAIN showing a full table scan?',
    5, 4, 71, NOW() - INTERVAL 18 HOUR),
(14, 'Indexing strategy for a forum-like application',
    'Foreign keys should always be indexed. created_at is a good candidate for sorting queries. Composite indexes when you filter by two columns together. Here''s my schema and the performance difference.',
    2, 4, 48, NOW() - INTERVAL 4 DAY),
(15, '5 queries every student project should have',
    'SELECT with JOIN, aggregate with GROUP BY, subquery, INSERT, and a simple WHERE filter. If your project has these 5, you''ve covered all the SQL topics your professor will look for.',
    1, 4, 94, NOW() - INTERVAL 5 DAY),
(16, 'My first full-stack project — what I learned',
    'I built a task manager with PHP and MySQL for my web dev class. Took me 3 weeks. Here''s what went wrong: I didn''t plan the schema first, I wrote HTML inside PHP strings, and I forgot to hash passwords. Fixed all three. Learning by doing works.',
    4, 5, 37, NOW() - INTERVAL 1 DAY),
(17, 'Rate my forum project — built with plain PHP',
    'Just finished my final project. It''s a mini forum with user auth, posts, and comments. No frameworks, just vanilla PHP and MySQL. Looking for feedback before I present it to my professor.',
    4, 5, 62, NOW() - INTERVAL 3 DAY);

INSERT INTO comments (id, body, user_id, post_id, score, created_at) VALUES
(7, 'flexbox with justify-content and align-items both set to center. That''s it. Done.', 2, 4, 12, NOW() - INTERVAL 46 HOUR),
(8, 'grid with place-items: center is even fewer keystrokes.', 3, 4, 8, NOW() - INTERVAL 45 HOUR),
(9, 'margin: auto still works. People overthink this.', 1, 4, 5, NOW() - INTERVAL 44 HOUR),
(10, 'Wait, :has() is supported now? Since when?', 5, 5, 15, NOW() - INTERVAL 95 HOUR),
(11, 'Since 2024. It''s safe to use now, 95% global support.', 1, 5, 9, NOW() - INTERVAL 94 HOUR),
(12, 'This is actually genius. I love CSS-only solutions.', 3, 5, 7, NOW() - INTERVAL 93 HOUR),
(13, '100% agree. People reach for React before they even write a single line of HTML.', 2, 6, 14, NOW() - INTERVAL 118 HOUR),
(14, 'But what about interactivity? Like a carousel or a modal?', 4, 6, 6, NOW() - INTERVAL 117 HOUR),
(15, 'You can do a lot with details/summary and dialog elements now.', 1, 6, 11, NOW() - INTERVAL 116 HOUR),
(16, 'PHP 8 actually slaps. Match expressions alone are worth the upgrade.', 3, 7, 8, NOW() - INTERVAL 22 HOUR),
(17, 'The hate is from people who haven''t touched it since PHP 4.', 5, 7, 13, NOW() - INTERVAL 21 HOUR),
(18, 'Facebook still runs PHP. That''s the best argument.', 1, 7, 10, NOW() - INTERVAL 20 HOUR),
(19, 'I keep a cheat sheet for this taped to my monitor.', 2, 8, 4, NOW() - INTERVAL 70 HOUR),
(20, 'empty() catches 0 and "" which trips me up every time.', 4, 8, 6, NOW() - INTERVAL 69 HOUR),
(21, 'Good take. Learn simple first, then upgrade.', 5, 9, 7, NOW() - INTERVAL 140 HOUR),
(22, 'I used PDO for my project and honestly it was fine. Not that hard.', 3, 9, 5, NOW() - INTERVAL 139 HOUR),
(23, 'The 3-color constraint is such a good idea. Stealing this.', 1, 10, 18, NOW() - INTERVAL 11 HOUR),
(24, 'Show us the portfolio!', 2, 10, 12, NOW() - INTERVAL 10 HOUR),
(25, 'What accent color did you pick?', 5, 10, 9, NOW() - INTERVAL 9 HOUR),
(26, 'I use a different approach — I just screenshot and measure manually.', 4, 11, 6, NOW() - INTERVAL 46 HOUR),
(27, 'CSS custom properties are a game changer for consistency.', 1, 11, 11, NOW() - INTERVAL 45 HOUR),
(28, 'The typography tip about line-height is so underrated.', 2, 12, 14, NOW() - INTERVAL 70 HOUR),
(29, 'Also: don''t use justify alignment for body text. Ragged right is more readable.', 3, 12, 10, NOW() - INTERVAL 69 HOUR),
(30, 'Bookmarking this. My JOINs have been painful.', 4, 13, 8, NOW() - INTERVAL 17 HOUR),
(31, 'EXPLAIN is the most underused tool in MySQL.', 1, 13, 15, NOW() - INTERVAL 16 HOUR),
(32, 'I literally just added indexes to my project after reading this.', 5, 13, 11, NOW() - INTERVAL 15 HOUR),
(33, 'This should be the first thing they teach about databases.', 3, 14, 7, NOW() - INTERVAL 94 HOUR),
(34, 'Saved me from a failing grade. My queries were so slow before.', 4, 14, 9, NOW() - INTERVAL 93 HOUR),
(35, 'Literally the 5 queries in our Yapr schema. Nice.', 2, 15, 13, NOW() - INTERVAL 120 HOUR),
(36, 'GROUP BY is the one students struggle with the most.', 1, 15, 6, NOW() - INTERVAL 119 HOUR),
(37, 'I made ALL of these mistakes on my first project.', 3, 16, 10, NOW() - INTERVAL 22 HOUR),
(38, 'The HTML-inside-PHP-strings mistake is real. Heredoc syntax helps.', 2, 16, 8, NOW() - INTERVAL 21 HOUR),
(39, 'Same here. I learned more from fixing bugs than from the lectures.', 5, 16, 6, NOW() - INTERVAL 20 HOUR),
(40, 'Looks good! How did you handle the commenting system?', 3, 17, 5, NOW() - INTERVAL 70 HOUR),
(41, 'I see you used mysqli_real_escape_string — nice, most people skip security.', 2, 17, 12, NOW() - INTERVAL 69 HOUR),
(42, 'Question: how did you seed your database for the demo?', 1, 17, 7, NOW() - INTERVAL 68 HOUR),
(43, 'Pro tip: use password_hash() if you haven''t already.', 5, 17, 9, NOW() - INTERVAL 67 HOUR),
(44, 'I like the Tailwind approach. Clean and fast.', 3, 17, 4, NOW() - INTERVAL 66 HOUR),
(45, 'Does it have pagination yet? That would be a nice addition.', 1, 17, 6, NOW() - INTERVAL 65 HOUR),
(46, 'Presenting this to my class next week. Thanks for the inspiration.', 4, 17, 3, NOW() - INTERVAL 64 HOUR);

INSERT INTO votes (user_id, target_id, target_type, value) VALUES
(1,4,'post',1),(2,4,'post',1),(3,4,'post',-1),
(1,5,'post',1),(3,5,'post',1),(4,5,'post',1),(5,5,'post',1),
(1,6,'post',1),(4,6,'post',1),(5,6,'post',1),
(1,7,'post',1),(3,7,'post',1),
(2,8,'post',1),
(2,9,'post',1),(3,9,'post',1),
(1,10,'post',1),(2,10,'post',1),(4,10,'post',1),(5,10,'post',1),
(1,11,'post',1),(3,11,'post',1),
(2,12,'post',1),(3,12,'post',1),(4,12,'post',1),
(1,13,'post',1),(2,13,'post',1),
(3,14,'post',1),
(1,15,'post',1),(2,15,'post',1),(3,15,'post',1),
(1,16,'post',1),
(2,17,'post',1),(3,17,'post',1),(5,17,'post',1);
