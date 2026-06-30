-- ForumHub Database Schema

CREATE DATABASE IF NOT EXISTS forumhub
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE forumhub;

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
