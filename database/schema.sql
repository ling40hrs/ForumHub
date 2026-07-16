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
-- All passwords are plaintext 'a' for demo purposes.

INSERT INTO users (id, username, email, password, bio, karma) VALUES
(1, 'ada', 'ada@yapr.dev', 'a', 'CS student who builds things for the web. Moderator of r/webdev.', 1280),
(2, 'linus', 'linus@yapr.dev', 'a', 'PHP enthusiast and open-source contributor.', 960),
(3, 'mira', 'mira@yapr.dev', 'a', 'UI/UX designer who codes. Loves typography and dark mode.', 2040),
(4, 'john', 'john@yapr.dev', 'a', 'Learning web development and building cool stuff.', 150),
(5, 'jane', 'jane@yapr.dev', 'a', 'Full-stack developer. Ask me about databases.', 720);

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
