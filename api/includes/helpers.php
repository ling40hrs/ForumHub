<?php

declare(strict_types=1);

// Presentation helpers for the Yapr static frontend (no framework).

function esc($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function avatarHtml(array $user, int $size = 10): string {
    $username = $user['username'] ?? 'guest';
    $initial = esc(mb_strtoupper(mb_substr($username, 0, 1)));
    $color = esc($user['avatar_color'] ?? '#ff4500');
    $px = $size * 4;

    $initialCircle = sprintf(
        '<div style="width:%1$dpx;height:%1$dpx;background-color:%2$s"'
        . ' class="flex items-center justify-center rounded-full border-2 border-line text-white font-semibold animate-scale-in">%3$s</div>',
        $px, $color, $initial
    );

    $url = $user['avatar_url'] ?? '';
    if ($url === '') {
        $url = 'https://api.dicebear.com/9.x/bottts/svg?seed=' . rawurlencode($username);
    }

    $fallback = "'" . str_replace('"', '&quot;', $initialCircle) . "'";
    return sprintf(
        '<img src="%1$s" alt="%2$s" width="%3$d" height="%3$d" loading="lazy"'
        . ' style="width:%3$dpx;height:%3$dpx;object-fit:cover;border-radius:9999px"'
        . ' class="rounded-full border-2 border-line object-cover"'
        . ' onerror="this.onerror=null;this.outerHTML=%4$s">',
        esc($url), esc($username), $px, $fallback
    );
}

function postCardHtml(array $post, int $index = 0): string {
    $author = $post['author']['username'] ?? 'unknown';
    $community = esc($post['community'] ?? '');
    $slug = esc($post['community_slug'] ?? '');
    $title = esc($post['title'] ?? '');
    $excerpt = esc(mb_substr($post['body'] ?? '', 0, 160));
    $score = esc($post['score'] ?? 0);
    $comments = esc($post['comments_count'] ?? 0);
    $time = esc($post['time'] ?? '');
    $id = (int) ($post['id'] ?? 0);
    $delay = min($index, 7) * 55;
    $comm = $community !== ''
        ? '<a href="community.php?slug=' . $slug . '" class="font-display font-semibold text-pop transition hover:underline">' . $community . '</a>'
        : '';
    return <<<HTML
    <div class="reveal" style="--reveal-delay:{$delay}ms">
      <article class="card flex overflow-hidden">
        <div class="vote-rail" data-score="{$score}">
          <button type="button" class="vote-btn is-up" aria-label="Upvote">▲</button>
          <span class="vote-score">{$score}</span>
          <button type="button" class="vote-btn is-down" aria-label="Downvote">▼</button>
        </div>
        <div class="min-w-0 flex-1 p-4">
          <div class="mb-2 flex flex-wrap items-center gap-2 text-sm text-ink-faint">
            {$comm}
            <span>·</span>
            <span>Posted by <span class="font-medium text-ink-soft">{$author}</span></span>
            <span>·</span>
            <span>{$time}</span>
          </div>
          <a href="post.php?id={$id}" class="block group">
            <h2 class="font-display text-xl font-semibold leading-snug text-ink transition group-hover:text-pop">{$title}</h2>
            <p class="mt-1.5 line-clamp-3 text-sm leading-relaxed text-ink-soft">{$excerpt}</p>
          </a>
          <div class="mt-3 flex items-center gap-3 text-sm text-ink-faint">
            <a href="post.php?id={$id}" class="pill bg-night-raised text-ink-soft transition hover:bg-pop hover:text-white">💬 {$comments} comments</a>
          </div>
        </div>
      </article>
    </div>
HTML;
}

function communityCardHtml(array $c, int $index = 0): string {
    $name = esc($c['name'] ?? '');
    $slug = esc($c['slug'] ?? '');
    $desc = esc($c['description'] ?? '');
    $members = esc($c['members_count'] ?? 0);
    $initial = esc(mb_strtoupper(mb_substr($c['name'] ?? '', 0, 1)));
    $delay = min($index, 7) * 55;
    return <<<HTML
    <div class="reveal" style="--reveal-delay:{$delay}ms">
      <a href="community.php?slug={$slug}" class="card flex items-start gap-3 p-3">
        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-pop font-display text-lg font-black text-white">{$initial}</span>
        <div class="min-w-0">
          <h3 class="font-display font-semibold text-ink">{$name}</h3>
          <p class="mt-0.5 line-clamp-2 text-xs text-ink-faint">{$desc}</p>
          <p class="mt-1 text-xs font-medium text-ink-soft">{$members} members</p>
        </div>
      </a>
    </div>
HTML;
}

function commentItemHtml(array $c, int $depth = 0, int $index = 0): string {
    $author = $c['author']['username'] ?? 'unknown';
    $body = esc($c['body'] ?? '');
    $time = esc($c['time'] ?? '');
    $score = esc($c['score'] ?? 0);
    $indent = $depth > 0 ? 'ml-6 border-l-2 border-line pl-4' : '';
    $delay = min($index, 7) * 55;
    return <<<HTML
    <div class="reveal py-3 {$indent}" style="--reveal-delay:{$delay}ms">
      <div class="flex flex-wrap items-center gap-2 text-xs text-ink-faint">
        <span class="font-semibold text-ink-soft">{$author}</span>
        <span>·</span><span>{$time}</span>
      </div>
      <p class="mt-1 text-sm leading-relaxed text-ink-soft">{$body}</p>
      <div class="mt-1.5 flex items-center gap-3 text-xs font-medium text-ink-faint">
        <span class="inline-flex cursor-pointer items-center gap-1 rounded-full bg-night-raised px-2 py-0.5 transition hover:text-pop">▲ {$score}</span>
        <span class="cursor-pointer transition hover:text-pop">Reply</span>
      </div>
    </div>
HTML;
}
