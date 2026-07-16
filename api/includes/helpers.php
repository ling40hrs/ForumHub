<?php

function timeAgo(string $datetime): string {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'just now';
    }

    if ($diff < 3600) {
        return floor($diff / 60) . 'm';
    }

    if ($diff < 86400) {
        return floor($diff / 3600) . 'h';
    }

    if ($diff < 604800) {
        return floor($diff / 86400) . 'd';
    }

    return date('M j', $timestamp);
}

function escapeHtml($value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function avatarHtml(array $user, int $size = 10): string {
    $username = $user['username'] ?? 'guest';

    $initial = mb_strtoupper(mb_substr($username, 0, 1));
    $initial = escapeHtml($initial);

    $color = escapeHtml($user['avatar_color'] ?? '#ff4500');

    $px = $size * 4;

    $initialCircle = '<div style="width:' . $px . 'px;height:' . $px . 'px;background-color:' . $color . '"';
    $initialCircle .= ' class="flex items-center justify-center rounded-full border-2 border-line text-white font-semibold">';
    $initialCircle .= $initial . '</div>';

    $avatarUrl = $user['avatar_url'] ?? '';

    if ($avatarUrl === '') {
        return $initialCircle;
    }

    $result = '<img src="' . escapeHtml($avatarUrl) . '" alt="' . escapeHtml($username) . '"';
    $result .= ' width="' . $px . '" height="' . $px . '" class="rounded-full" loading="lazy">';

    return $result;
}

function postCardHtml(array $post): string {
    $author = $post['author']['username'] ?? 'unknown';
    $community = escapeHtml($post['community'] ?? '');
    $slug = escapeHtml($post['community_slug'] ?? '');
    $title = escapeHtml($post['title'] ?? '');
    $excerpt = escapeHtml(mb_substr($post['body'] ?? '', 0, 160));
    $score = escapeHtml($post['score'] ?? 0);
    $comments = escapeHtml($post['comments_count'] ?? 0);
    $time = escapeHtml($post['time'] ?? '');
    $id = intval($post['id'] ?? 0);
    $myVote = intval($post['my_vote'] ?? 0);
    $upClass   = 'vote-btn is-up' . ($myVote === 1 ? ' is-active' : '');
    $downClass = 'vote-btn is-down' . ($myVote === -1 ? ' is-active' : '');
    $voteSection = <<<HTML
        <form class="vote-rail order-last lg:order-first" action="vote-handler.php" method="post">
          <input type="hidden" name="target_id" value="{$id}">
          <input type="hidden" name="target_type" value="post">
          <button type="submit" name="vote" value="1" class="{$upClass}" aria-label="Upvote">▲</button>
          <span class="vote-score">{$score}</span>
          <button type="submit" name="vote" value="-1" class="{$downClass}" aria-label="Downvote">▼</button>
          <a href="post.php?id={$id}" class="lg:hidden ml-auto flex items-center gap-1 text-sm text-ink-faint">💬 {$comments}</a>
        </form>
HTML;

    if ($community !== '') {
        $communityLink = '<a href="community.php?slug=' . $slug . '"'
            . ' class="font-display font-semibold text-pop transition hover:underline">'
            . $community . '</a>';
    } else {
        $communityLink = '';
    }

    return <<<HTML
      <article class="card flex flex-col lg:flex-row overflow-hidden">
        {$voteSection}
        <div class="min-w-0 flex-1 p-4">
          <div class="mb-2 flex flex-wrap items-center gap-2 text-sm text-ink-faint">
            {$communityLink}
            <span>·</span>
            <span>Posted by <span class="font-medium text-ink-soft">{$author}</span></span>
            <span>·</span>
            <span>{$time}</span>
          </div>
          <a href="post.php?id={$id}" class="block group">
            <h2 class="font-display text-lg lg:text-xl font-semibold leading-snug text-ink transition group-hover:text-pop">{$title}</h2>
            <p class="mt-1.5 line-clamp-2 lg:line-clamp-3 text-sm leading-relaxed text-ink-soft">{$excerpt}</p>
          </a>
          <div class="mt-3 hidden lg:flex items-center gap-3 text-sm text-ink-faint">
            <a href="post.php?id={$id}" class="pill bg-night-raised text-ink-soft transition hover:bg-pop hover:text-white">💬 {$comments} comments</a>
          </div>
        </div>
      </article>
HTML;
}

function communityCardHtml(array $c, int $index = 0): string {
    $name = escapeHtml($c['name'] ?? '');
    $slug = escapeHtml($c['slug'] ?? '');
    $desc = escapeHtml($c['description'] ?? '');
    $members = escapeHtml($c['members_count'] ?? 0);
    $initial = escapeHtml(mb_strtoupper(mb_substr($c['name'] ?? '', 0, 1)));
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

function commentIndentStyle(int $depth): string {
    if ($depth > 0) {
        return 'border-l-2 border-line pl-4';
    }
    return '';
}

function commentActionsHtml(array $c, int $postId, int $depth): string {
    $id = intval($c['id'] ?? 0);
    $score = escapeHtml($c['score'] ?? 0);
    $myVote = intval($c['my_vote'] ?? 0);
    $upClass   = 'vote-pill is-up inline-flex items-center gap-1 rounded-full bg-night-raised px-2 py-0.5 transition hover:text-pop' . ($myVote === 1 ? ' is-active' : '');
    $downClass = 'vote-pill is-down inline-flex items-center gap-1 rounded-full bg-night-raised px-2 py-0.5 transition hover:text-pop' . ($myVote === -1 ? ' is-active' : '');

    $replyHtml = '';
    if ($depth < 5) {
        $replyHtml = '<button type="button" class="reply-btn text-xs font-medium text-ink-faint hover:text-pop" data-comment-id="' . $id . '">Reply</button>';
    }

    $inlineFormHtml = <<<HTML
      <div class="inline-reply hidden mt-2" id="reply-form-{$id}">
        <form class="js-reply-form space-y-2" data-comment-id="{$id}">
          <input type="hidden" name="post_id" value="{$postId}">
          <input type="hidden" name="parent_id" value="{$id}">
          <textarea name="body" rows="2" placeholder="Write a reply..." class="field text-sm"></textarea>
          <div class="flex items-center gap-2">
            <button type="submit" class="btn-pop text-xs py-1 px-3">Post Reply</button>
            <button type="button" class="cancel-reply text-xs text-ink-faint hover:text-ink">Cancel</button>
          </div>
        </form>
      </div>
HTML;

    return <<<HTML
        <div class="mt-1.5 flex items-center gap-3 text-xs font-medium text-ink-faint">
        <form class="inline-flex items-center gap-1" action="vote-handler.php" method="post">
          <input type="hidden" name="target_id" value="{$id}">
          <input type="hidden" name="target_type" value="comment">
          <button type="submit" name="vote" value="1" class="{$upClass}" aria-label="Upvote">▲</button>
          <span class="font-semibold">{$score}</span>
          <button type="submit" name="vote" value="-1" class="{$downClass}" aria-label="Downvote">▼</button>
        </form>
        {$replyHtml}
      </div>
      {$inlineFormHtml}
HTML;
}

function commentItemHtml(array $c, int $postId = 0, int $depth = 0, int $index = 0): string {
    $author = $c['author']['username'] ?? 'unknown';
    $body = escapeHtml($c['body'] ?? '');
    $time = escapeHtml($c['time'] ?? '');
    $id = intval($c['id'] ?? 0);

    $indent = commentIndentStyle($depth);

    if ($depth > 0) {
        $marginStyle = 'margin-left:' . ($depth * 24) . 'px;';
    } else {
        $marginStyle = '';
    }

    $delay = min($index, 7) * 55;
    $actions = commentActionsHtml($c, $postId, $depth);
    $avatar = avatarHtml($c['author'], 6);

    return <<<HTML
    <div class="reveal py-3{$indent}" style="{$marginStyle}--reveal-delay:{$delay}ms;">
      <div class="flex flex-wrap items-center gap-2 text-xs text-ink-faint">
        {$avatar}
        <span class="font-semibold text-ink-soft">{$author}</span>
        <span>·</span><span>{$time}</span>
      </div>
      <p class="mt-1 text-sm leading-relaxed text-ink-soft">{$body}</p>
      {$actions}
    </div>
HTML;
}

function commentTreeHtml(array $comment, array $repliesByParent, int $postId, int $depth = 0): string {
    $html = '<div class="comment-thread" data-comment-id="' . $comment['id'] . '">';
    $html .= commentItemHtml($comment, $postId, $depth);

    $children = $repliesByParent[$comment['id']] ?? [];

    foreach ($children as $reply) {
        $html .= commentTreeHtml($reply, $repliesByParent, $postId, $depth + 1);
    }

    $html .= '</div>';
    return $html;
}

function isCommunityMember($conn, int $userId, int $communityId): bool {
    if ($userId === 0) {
        return false;
    }
    $res = mysqli_query($conn,
        "SELECT 1 FROM community_members
         WHERE user_id = $userId AND community_id = $communityId LIMIT 1");
    return mysqli_num_rows($res) > 0;
}

function commentDepth($conn, int $commentId): int {
    $depth = 0;
    $curr = $commentId;
    while ($curr > 0) {
        $r = mysqli_query($conn, "SELECT parent_id FROM comments WHERE id = $curr");
        $row = mysqli_fetch_assoc($r);
        if (!$row || $row['parent_id'] === null) {
            break;
        }
        $depth++;
        $curr = $row['parent_id'];
    }
    return $depth;
}

function paginationHtml(int $page, int $totalPages, string $baseUrl): string {
    if ($totalPages <= 1) {
        return '';
    }

    $html = '<nav class="mt-6 flex items-center justify-center gap-1 text-sm font-medium" aria-label="Pagination">';

    if ($page > 1) {
        $prev = $page - 1;
        $html .= '<a href="' . $baseUrl . '&amp;page=' . $prev . '" class="pill text-ink-soft hover:bg-night-raised hover:text-ink">&larr; Prev</a>';
    }

    $start = max(1, $page - 2);
    $end = min($totalPages, $page + 2);

    if ($start > 1) {
        $html .= '<a href="' . $baseUrl . '&amp;page=1" class="pill text-ink-soft hover:bg-night-raised hover:text-ink">1</a>';
        if ($start > 2) {
            $html .= '<span class="px-1 text-ink-faint">&hellip;</span>';
        }
    }

    for ($p = $start; $p <= $end; $p++) {
        if ($p === $page) {
            $html .= '<span class="pill bg-pop text-white">' . $p . '</span>';
        } else {
            $html .= '<a href="' . $baseUrl . '&amp;page=' . $p . '" class="pill text-ink-soft hover:bg-night-raised hover:text-ink">' . $p . '</a>';
        }
    }

    if ($end < $totalPages) {
        if ($end < $totalPages - 1) {
            $html .= '<span class="px-1 text-ink-faint">&hellip;</span>';
        }
        $html .= '<a href="' . $baseUrl . '&amp;page=' . $totalPages . '" class="pill text-ink-soft hover:bg-night-raised hover:text-ink">' . $totalPages . '</a>';
    }

    if ($page < $totalPages) {
        $next = $page + 1;
        $html .= '<a href="' . $baseUrl . '&amp;page=' . $next . '" class="pill text-ink-soft hover:bg-night-raised hover:text-ink">Next &rarr;</a>';
    }

    $html .= '</nav>';
    return $html;
}
