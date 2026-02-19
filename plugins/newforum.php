<?php
declare(strict_types=1);
global $parser;
require_once __DIR__.'/../inc/header.php';
require_once __DIR__.'/../inc/jbbcode/Parser.php';
require_once __DIR__.'/../inc/page.class.php';

// Sanitize and normalise GET parameters
$_GET['act']       = array_key_exists('act', $_GET)       && ctype_alpha($_GET['act'])       ? strtolower(trim($_GET['act']))  : '';
$_GET['viewtopic'] = array_key_exists('viewtopic', $_GET) && ctype_digit($_GET['viewtopic']) ? $_GET['viewtopic']              : null;
$_GET['viewforum'] = array_key_exists('viewforum', $_GET) && ctype_digit($_GET['viewforum']) ? $_GET['viewforum']              : null;
$_GET['reply']     = array_key_exists('reply', $_GET)     && ctype_digit($_GET['reply'])     ? $_GET['reply']                  : null;
$_GET['forum']     = array_key_exists('forum', $_GET)     && ctype_digit($_GET['forum'])     ? $_GET['forum']                  : null;
$_GET['topic']     = array_key_exists('topic', $_GET)     && ctype_digit($_GET['topic'])     ? $_GET['topic']                  : null;
$_GET['post']      = array_key_exists('post', $_GET)      && ctype_digit($_GET['post'])      ? $_GET['post']                   : null;

// Derive action from shorthand parameters
if (!empty($_GET['viewtopic']) && $_GET['act'] !== 'quote') {
    $_GET['act'] = 'viewtopic';
}
if (!empty($_GET['viewforum'])) {
    $_GET['act'] = 'viewforum';
}
if (!empty($_GET['reply'])) {
    $_GET['act'] = 'reply';
}

// Initialise the JBBCode parser
$parser = new JBBCode\Parser();
$parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

// Forum ban check
$db->query('SELECT days FROM bans WHERE type = \'forum\' AND id = ?');
$db->execute([$user_class->id]);
if ($db->count()) {
    echo Message('You\'ve been banned from the forum. Ban time remaining: '.time_format($db->result() * 86400), 'Error', true);
}

// Flash success message
if (array_key_exists('success', $_SESSION)) {
    echo Message($_SESSION['success'], 'Success');
    unset($_SESSION['success']);
}
?><tr>
    <th class="content-head">New Forum</th>
</tr>
<tr>
    <td class="content">
        <?php echo $_GET['act'] !== 'managesub'
            ? '<p><a href="plugins/newforum.php?act=managesub" class="pure-button pure-button-grey">Manage Subscriptions</a></p>'
            : '<p><a href="plugins/newforum.php" class="pure-button pure-button-grey">&larr; Back to Forum</a></p>'; ?>
    </td>
</tr><?php
if ($_GET['act'] !== 'viewtopic') {
    ?><tr>
    <td class="content"><?php
}

// Route to the appropriate action
switch ($_GET['act']) {
    case 'viewforum':
        nf_viewforum($db, $user_class, $parser);
        break;
    case 'viewtopic':
        nf_viewtopic($db, $user_class, $parser);
        break;
    case 'reply':
        nf_reply($db, $user_class, $parser);
        break;
    case 'newtopic':
        nf_newtopic($db, $user_class, $parser);
        break;
    case 'quote':
        nf_quote($db, $user_class, $parser);
        break;
    case 'edit':
        nf_edit($db, $user_class, $parser);
        break;
    case 'move':
        nf_move($db, $user_class, $parser);
        break;
    case 'lock':
        nf_lock($db, $user_class, $parser);
        break;
    case 'pin':
        nf_pin($db, $user_class, $parser);
        break;
    case 'delepost':
        nf_delepost($db, $user_class, $parser);
        break;
    case 'deletopic':
        nf_deletopic($db, $user_class, $parser);
        break;
    case 'sub':
        nf_subscribe($db, $user_class, $parser);
        break;
    case 'managesub':
        nf_manage_subscriptions($db, $user_class, $parser);
        break;
    default:
        nf_index($db, $user_class, $parser);
        break;
}

// ─────────────────────────────────────────────────────────────────────────────
// FORUM INDEX
// ─────────────────────────────────────────────────────────────────────────────
function nf_index($db, $user_class, $parser): void
{
    // Public boards
    $db->query('SELECT * FROM forum_boards WHERE fb_auth = \'public\' AND fb_bin = 0 ORDER BY fb_id');
    $db->execute();
    $rows = $db->fetch();
    ?>
    <table class="pure-table pure-table-horizontal center" width="100%">
        <thead>
            <tr>
                <th width="40%">Forum</th>
                <th width="10%">Posts</th>
                <th width="10%">Topics</th>
                <th width="40%">Last Post</th>
            </tr>
        </thead>
        <tbody><?php
    nf_render_board_rows($rows, $db);
    ?></tbody>
    </table><?php

    // Staff boards (admin only)
    if ($user_class->admin > 0) {
        $db->query('SELECT * FROM forum_boards WHERE fb_auth = \'staff\' AND fb_bin = 0 ORDER BY fb_id');
        $db->execute();
        $rows = $db->fetch();
        ?><h2 class="center">Staff Boards</h2>
        <table class="pure-table pure-table-horizontal center" width="100%">
            <thead>
                <tr>
                    <th width="40%">Forum</th>
                    <th width="10%">Posts</th>
                    <th width="10%">Topics</th>
                    <th width="40%">Last Post</th>
                </tr>
            </thead>
            <tbody><?php
        nf_render_board_rows($rows, $db);
        ?></tbody>
        </table><?php
    }
}

/** Renders <tr> rows for a set of forum_boards result rows. */
function nf_render_board_rows(?array $rows, $db): void
{
    if ($rows !== null) {
        foreach ($rows as $row) {
            ?><tr>
                <td>
                    <a href="plugins/newforum.php?viewforum=<?php echo $row['fb_id']; ?>" class="bold">
                        <?php echo format($row['fb_name'] ?? '[Unnamed]'); ?>
                    </a><br />
                    <span class="small"><?php echo format($row['fb_desc'] ?? ''); ?></span>
                </td>
                <td><?php echo format($row['fb_posts'] ?? 0); ?></td>
                <td><?php echo format($row['fb_topics'] ?? 0); ?></td>
                <td><?php
            if ($row['fb_latest_topic']) {
                $poster = $row['fb_latest_poster'] ? new User($row['fb_latest_poster']) : (object)['formattedname' => 'None'];
                $db->query('SELECT ft_name FROM forum_topics WHERE ft_id = ?');
                $db->execute([$row['fb_latest_topic']]);
                $date = new DateTime($row['fb_latest_time']);
                echo $date->format('F d, Y g:i:sa'); ?><br />
                            In: <a href="plugins/newforum.php?viewtopic=<?php echo $row['fb_latest_topic']; ?>&amp;latest">
                                <?php echo format($db->result() ?? '[Untitled]'); ?>
                            </a><br />
                            By: <?php echo $poster->formattedname ?: 'Unknown';
            } else {
                echo 'No posts yet';
            } ?></td>
            </tr><?php
        }
    } else {
        ?><tr>
            <td colspan="4" class="center"><p>There are no boards in this category.</p></td>
        </tr><?php
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// VIEW FORUM (topic listing)
// ─────────────────────────────────────────────────────────────────────────────
function nf_viewforum($db, $user_class, $parser): void
{
    if (empty($_GET['viewforum'])) {
        echo Message('You didn\'t select a valid board', 'Error', true);
    }
    $db->query('SELECT fb_name, fb_auth, fb_owner FROM forum_boards WHERE fb_id = ?');
    $db->execute([$_GET['viewforum']]);
    if (!$db->count()) {
        echo Message('That board doesn\'t exist', 'Error', true);
    }
    $board = $db->fetch(true);
    nf_accessCheck($board, $user_class);
    ?>
    <div class="big">
        <a href="plugins/newforum.php">Index</a> &rarr;
        <a href="plugins/newforum.php?viewforum=<?php echo $_GET['viewforum']; ?>"><?php echo format($board['fb_name']); ?></a>
        <?php if ($_GET['viewforum'] != 1 || $user_class->admin == 1): ?>
            <br /><br />
            <a href="plugins/newforum.php?act=newtopic&amp;forum=<?php echo $_GET['viewforum']; ?>" class="pure-button">
                Create New Topic
            </a>
        <?php endif; ?>
    </div><br /><?php

    $db->query('SELECT COUNT(ft_id) FROM forum_topics WHERE ft_board = ?');
    $db->execute([$_GET['viewforum']]);
    $cnt = $db->result();
    $pages = new Paginator($cnt);

    $db->query('SELECT ft_id, ft_name, ft_creation_time, ft_creation_user,
                       ft_latest_time, ft_latest_user, ft_latest_post,
                       ft_locked, ft_pinned, id AS subbed
                FROM forum_topics
                LEFT JOIN forum_subscriptions ON topic = ft_id AND userid = ?
                WHERE ft_board = ?
                ORDER BY ft_pinned DESC, ft_latest_time DESC
                LIMIT '.(int)$pages->limit_start.', '.(int)$pages->limit_end);
    $db->execute([$user_class->id, $_GET['viewforum']]);
    $topics = $db->fetch();
    echo $pages->display_pages();
    ?>
    <table class="pure-table pure-table-horizontal center" width="100%">
        <thead>
            <tr>
                <th width="40%">Topic</th>
                <th width="10%">Posts</th>
                <th width="25%">Started</th>
                <th width="25%">Latest Post</th>
            </tr>
        </thead>
        <tbody><?php
    if ($topics !== null) {
        foreach ($topics as $topic) {
            $date_created = new DateTime($topic['ft_creation_time']);
            $date_latest  = $topic['ft_latest_time'] ? new DateTime($topic['ft_latest_time']) : null;
            $creator = $topic['ft_creation_user'] ? new User($topic['ft_creation_user']) : (object)['formattedname' => 'None'];
            ?><tr>
                <td><?php
            echo $topic['ft_pinned'] ? '<img src="/images/silk/exclamation.png" title="Pinned" alt="Pinned" /> ' : '';
            echo $topic['ft_locked'] ? '<img src="/images/silk/lock.png" title="Locked" alt="Locked" /> ' : '';
            ?><a href="plugins/newforum.php?viewtopic=<?php echo $topic['ft_id']; ?>"><?php echo format($topic['ft_name'] ?? '[Untitled]'); ?></a>
                    <?php echo isset($topic['subbed']) ? ' <img src="/images/silk/eye.png" title="Subscribed" alt="[Subscribed]" />' : ''; ?>
                </td>
                <td><?php echo nf_getPostCount($topic['ft_id'], 'posts_topics') ?: 0; ?></td>
                <td>
                    <?php echo $creator->formattedname ?: 'Unknown'; ?><br />
                    <span class="small"><?php echo $date_created->format('F d, Y g:i:sa'); ?></span>
                </td>
                <td><?php
            if ($topic['ft_latest_user'] && $date_latest !== null) {
                $poster = new User($topic['ft_latest_user']);
                echo $poster->formattedname ?: 'Unknown'; ?><br />
                        <span class="small"><?php echo $date_latest->format('F d, Y g:i:sa'); ?></span><br />
                        <a href="plugins/newforum.php?viewtopic=<?php echo $topic['ft_id']; ?>&amp;latest">
                            <img src="/images/silk/arrow_right.png" title="Go to latest post" alt="Go to latest post" />
                        </a><?php
            } else {
                echo 'No responses yet';
            } ?></td>
            </tr><?php
        }
    } else {
        ?><tr>
            <td colspan="4" class="center"><p>There are no topics yet.</p></td>
        </tr><?php
    } ?></tbody>
    </table>
    <?php echo $pages->display_pages();
}

// ─────────────────────────────────────────────────────────────────────────────
// VIEW TOPIC (post listing)
// ─────────────────────────────────────────────────────────────────────────────
function nf_viewtopic($db, $user_class, $parser): void
{
    $precache = [];
    if (empty($_GET['viewtopic'])) {
        echo Message('You didn\'t select a valid topic', 'Error', true);
    }
    $db->query('SELECT ft_id, ft_name, ft_board, ft_locked, ft_pinned, id AS subbed
                FROM forum_topics
                LEFT JOIN forum_subscriptions ON topic = ft_id AND userid = ?
                WHERE ft_id = ?');
    $db->execute([$user_class->id, $_GET['viewtopic']]);
    if (!$db->count()) {
        echo Message('That topic doesn\'t exist', 'Error', true);
    }
    $topic = $db->fetch(true);

    $db->query('SELECT fb_id, fb_name, fb_owner, fb_auth FROM forum_boards WHERE fb_id = ?');
    $db->execute([$topic['ft_board']]);
    if (!$db->count()) {
        echo Message('The board for this topic doesn\'t exist'.(nf_trashTopic($topic['ft_id']) ? '. This topic has been automatically deleted/recycled' : ''), 'Error', true);
    }
    $board = $db->fetch(true);
    ?><tr>
        <th class="content-head">
            <div class="big">
                <a href="plugins/newforum.php">Index</a> &rarr;
                <a href="plugins/newforum.php?viewforum=<?php echo $board['fb_id']; ?>"><?php echo format($board['fb_name']); ?></a> &rarr;
                <a href="plugins/newforum.php?viewtopic=<?php echo $topic['ft_id']; ?>"><?php echo format($topic['ft_name']); ?></a>
            </div>
        </th>
    </tr>
    <tr>
        <td class="content"><?php
    nf_accessCheck($board, $user_class);

    $postCount = nf_getPostCount($topic['ft_id'], 'posts_topics');
    $pages = new Paginator($postCount);
    if (array_key_exists('latest', $_GET) && isset($pages->num_pages) && $pages->num_pages > 0) {
        exit(header('Location: newforum.php?viewtopic='.$topic['ft_id'].'&page='.$pages->num_pages.'#latest'));
    }

    // Fetch blocked users
    $blocked = [];
    $db->query('SELECT blocked_id FROM users_blocked WHERE userid = ?');
    $db->execute([$user_class->id]);
    if ($db->count()) {
        foreach ($db->fetch() as $row) {
            $blocked[] = $row['blocked_id'];
        }
    }
    $extra = count($blocked) ? ' AND fp_poster NOT IN ('.implode(',', array_map('intval', $blocked)).')' : '';

    $db->query('SELECT * FROM forum_posts WHERE fp_topic = ?'.$extra.' ORDER BY fp_time ASC LIMIT '.(int)$pages->limit_start.', '.(int)$pages->limit_end);
    $db->execute([$topic['ft_id']]);
    $posts = $db->fetch();

    $csrfg = csrf_create('csrfg', false);

    // Admin controls
    if ($user_class->admin == 1) {
        $pinOpposite  = $topic['ft_pinned'] ? 'Unpin' : 'Pin';
        $lockOpposite = $topic['ft_locked'] ? 'Unlock' : 'Lock';
        ?>
        <div class="pure-g center">
            <div class="pure-u-1-2">
                <form action="plugins/newforum.php?act=move&amp;topic=<?php echo $topic['ft_id']; ?>" method="post" class="pure-form pure-form-aligned">
                    <?php echo csrf_create(); ?>
                    <div class="pure-control-group">
                        <label for="board">Move topic to</label>
                        <?php echo forums_boards('board'); ?>
                    </div>
                    <div class="pure-controls">
                        <button type="submit" class="pure-button pure-button-primary">Move</button>
                    </div>
                </form>
            </div>
            <div class="pure-u-1-2">
                <a href="plugins/newforum.php?act=pin&amp;topic=<?php echo $topic['ft_id']; ?>&amp;csrfg=<?php echo $csrfg; ?>">
                    <img src="../images/silk/exclamation.png" alt="<?php echo $pinOpposite; ?>" title="<?php echo $pinOpposite; ?>" />
                </a> &middot;
                <a href="plugins/newforum.php?act=lock&amp;topic=<?php echo $topic['ft_id']; ?>&amp;csrfg=<?php echo $csrfg; ?>">
                    <img src="../images/silk/lock.png" alt="<?php echo $lockOpposite; ?>" title="<?php echo $lockOpposite; ?>" />
                </a> &middot;
                <a href="plugins/newforum.php?act=deletopic&amp;topic=<?php echo $topic['ft_id']; ?>&amp;csrfg=<?php echo $csrfg; ?>">
                    <img src="../images/silk/delete.png" title="Delete Topic" alt="Delete Topic" />
                </a>
            </div>
        </div><?php
    }

    $subWhich = isset($topic['subbed']) ? 'Uns' : 'S';
    ?><div class="pure-g center"><?php
    if ($topic['ft_locked'] && $user_class->admin != 1) {
        ?><div class="pure-u-1-1 pure-info-message">This topic is locked. Only staff members can respond.</div><?php
    } else {
        $csrfReply = csrf_create();
        ?><div class="pure-u-1-1 center">
            <form action="plugins/newforum.php?reply=<?php echo $topic['ft_id']; ?>" method="post" class="pure-form pure-form-aligned">
                <?php echo $csrfReply; ?>
                <div class="pure-control-group">
                    <label for="message">Enter a response</label>
                    <textarea name="message" id="message" rows="7" cols="85%" required></textarea>
                </div>
                <div class="pure-controls">
                    <button type="submit" class="pure-button pure-button-primary">Post Response</button>
                </div>
            </form>
        </div><?php
    }
    ?></div>
    <div class="pure-g center">
        <div class="pure-u-1-6">
            <a href="plugins/newforum.php?act=sub&amp;topic=<?php echo $topic['ft_id']; ?>&amp;csrfg=<?php echo $csrfg; ?>" class="pure-button pure-button-grey">
                <?php echo $subWhich; ?>ubscribe
            </a>
        </div>
    </div>
</td>
</tr>
<tr>
    <th class="content-head">&nbsp;</th>
</tr>
<tr>
    <td class="content"><?php
    echo $pages->display_pages();
    ?>
    <table class="pure-table pure-table-horizontal" width="100%">
        <thead>
            <tr>
                <th width="25%">Poster</th>
                <th width="75%">Content</th>
            </tr>
        </thead>
        <tbody><?php
    if ($posts !== null) {
        $cnt = count($posts);
        $no  = (isset($_GET['page']) && (int)$_GET['page'] > 1) ? ($pages->items_per_page * (int)$_GET['page']) - $pages->items_per_page : 0;
        foreach ($posts as $post) {
            $date = new DateTime($post['fp_time']);
            ++$no;
            if (isset($precache[$post['fp_poster']])) {
                $memb = $precache[$post['fp_poster']];
            } else {
                $db->query('SELECT id FROM users WHERE id = ?');
                $db->execute([$post['fp_poster']]);
                if ($db->count()) {
                    $tmp  = new User($post['fp_poster']);
                    $memb = ['id' => $tmp->id, 'level' => $tmp->level, 'avatar' => $tmp->avatar, 'signature' => $tmp->signature];
                } else {
                    $memb = ['id' => 0, 'level' => 0, 'avatar' => '', 'signature' => ''];
                }
                $precache[$memb['id']] = $memb;
            }
            if ($post['fp_edit_times']) {
                $edit = new DateTime($post['fp_edit_time']);
                $post['fp_text'] .= "\n\n".'[i]Edited '.$edit->format('F d, Y g:i:sa').'. Reason: '.($post['fp_edit_reason'] ?: 'None').'[/i]';
            }
            ?><tr>
                <th class="center">Post #<?php
            $isLast = ($no == ($pages->limit_start + $cnt));
            echo $isLast ? '<a id="latest" href="plugins/newforum.php?viewtopic='.$topic['ft_id'].($pages->current_page > 1 ? '&amp;page='.$pages->current_page : '').'#latest">'.$no.'</a>' : $no;
            ?></th>
                <th class="center top">
                    <?php echo $date->format('F d, Y g:i:sa'); ?><br />
                    <span class="small">
                        <a href="plugins/newforum.php?act=quote&amp;viewtopic=<?php echo $topic['ft_id']; ?>&amp;quote=<?php echo $post['fp_id']; ?>&amp;csrfg=<?php echo $csrfg; ?>">
                            <img src="../images/silk/page_attach.png" title="Quote" alt="[Quote]" />
                        </a><?php
            if ($user_class->admin == 1) {
                if ($post['fp_poster'] == $user_class->id) {
                    ?><a href="plugins/newforum.php?act=edit&amp;topic=<?php echo $topic['ft_id']; ?>&amp;post=<?php echo $post['fp_id']; ?>&amp;csrfg=<?php echo $csrfg; ?>">
                                <img src="../images/silk/pencil_go.png" title="Edit" alt="[Edit]" />
                            </a><?php
                }
                ?><a href="plugins/newforum.php?act=delepost&amp;topic=<?php echo $topic['ft_id']; ?>&amp;post=<?php echo $post['fp_id']; ?>&amp;csrfg=<?php echo $csrfg; ?>">
                            <img src="../images/silk/page_delete.png" title="Delete" alt="[Delete]" />
                        </a><?php
            } ?></span>
                </th>
            </tr>
            <tr>
                <td><?php
            if ($memb['id']) {
                $poster = new User($post['fp_poster']);
                echo $poster->formattedname; ?><br />
                        <?php echo formatImage($poster->avatar); ?><br />
                        <?php echo nf_forums_rank(nf_getPostCount($post['fp_poster'], 'posts_user'));
            } else {
                echo '<span class="bold italic">Deleted user</span>';
            } ?></td>
                <td>
                    <?php echo nf_tag($parser->getAsHTML($parser->parse(nl2br(format($post['fp_text'])))), true); ?><br />
                    <hr width="50%" />
                    <?php echo $parser->getAsHTML($parser->parse(nl2br(format($memb['signature'])))); ?>
                </td>
            </tr><?php
        }
    } else {
        ?><tr>
            <td colspan="2" class="center"><p>No posts in this topic yet. Be the first to post!</p></td>
        </tr><?php
    } ?></tbody>
    </table>
    <?php echo $pages->display_pages(); ?><br /><br /><?php
    if (!$topic['ft_locked'] || ($user_class->admin == 1 && $topic['ft_locked'])) {
        if ($user_class->admin == 1 && $topic['ft_locked']) {
            echo Message('This topic is locked. Only staff members (with access) can respond');
        }
        if (!isset($csrfReply)) {
            $csrfReply = csrf_create();
        }
        ?><form action="plugins/newforum.php?reply=<?php echo $topic['ft_id']; ?>" method="post" class="pure-form pure-form-aligned">
            <?php echo $csrfReply; ?>
            <fieldset>
                <legend>Post a reply to this topic</legend>
                <textarea name="message" rows="7" cols="40" required></textarea>
                <button type="submit" class="pure-button pure-button-primary">Post Response</button>
            </fieldset>
        </form><?php
    } else {
        echo '<span class="italic">This topic has been locked, you can\'t respond</span>';
    }
    ?></td></tr><?php
}

// ─────────────────────────────────────────────────────────────────────────────
// CREATE NEW TOPIC
// ─────────────────────────────────────────────────────────────────────────────
function nf_newtopic($db, $user_class, $parser): void
{
    if (empty($_GET['forum'])) {
        echo Message('You didn\'t select a valid board', 'Error', true);
    }
    $db->query('SELECT fb_id, fb_name, fb_owner, fb_auth FROM forum_boards WHERE fb_id = ?');
    $db->execute([$_GET['forum']]);
    if (!$db->count()) {
        echo Message('That board doesn\'t exist', 'Error', true);
    }
    $board = $db->fetch(true);
    ?><div class="big">
        <a href="plugins/newforum.php">Index</a> &rarr;
        <a href="plugins/newforum.php?viewforum=<?php echo $board['fb_id']; ?>"><?php echo format($board['fb_name']); ?></a>
        <p>Topic Creation</p>
    </div><?php
    nf_accessCheck($board, $user_class);

    $errors = [];
    if (array_key_exists('submit', $_POST)) {
        if (!csrf_check('csrf', $_POST)) {
            echo Message(SECURITY_TIMEOUT_MESSAGE);
        }
        $_POST['name']    = array_key_exists('name', $_POST)    && is_string($_POST['name'])    ? strip_tags(trim($_POST['name']))    : null;
        $_POST['message'] = array_key_exists('message', $_POST) && is_string($_POST['message']) ? strip_tags(trim($_POST['message'])) : null;
        if (empty($_POST['name'])) {
            $errors[] = 'You didn\'t enter a valid topic name';
        }
        if (empty($_POST['message'])) {
            $errors[] = 'You didn\'t enter a valid message';
        }
        // Duplicate check
        $db->query('SELECT ft_id FROM forum_topics WHERE ft_name = ? AND ft_creation_user = ? ORDER BY ft_id DESC LIMIT 1');
        $db->execute([$_POST['name'], $user_class->id]);
        $existingTopic = $db->fetch(true);
        if ($existingTopic !== null) {
            $db->query('SELECT fp_id FROM forum_posts WHERE fp_text = ? AND fp_poster = ? AND fp_topic = ? ORDER BY fp_id DESC LIMIT 1');
            $db->execute([$_POST['message'], $user_class->id, $existingTopic['ft_id']]);
            if ($db->count()) {
                $errors[] = 'You\'ve already made that topic/post';
            }
        }
        if (count($errors)) {
            display_errors($errors);
        }
        $db->trans('start');
        $db->query('INSERT INTO forum_topics (ft_board, ft_name, ft_creation_user, ft_latest_user) VALUES (?, ?, ?, ?)');
        $db->execute([$board['fb_id'], $_POST['name'], $user_class->id, $user_class->id]);
        $topicID = $db->id();
        $db->query('INSERT INTO forum_posts (fp_board, fp_topic, fp_poster, fp_text) VALUES (?, ?, ?, ?)');
        $db->execute([$board['fb_id'], $topicID, $user_class->id, $_POST['message']]);
        $postID = $db->id();
        $db->query('UPDATE forum_topics SET ft_latest_post = ? WHERE ft_id = ?');
        $db->execute([$postID, $topicID]);
        $db->query('UPDATE forum_boards SET fb_topics = fb_topics + 1, fb_posts = fb_posts + 1, fb_latest_topic = ?, fb_latest_post = ?, fb_latest_poster = ?, fb_latest_time = NOW() WHERE fb_id = ?');
        $db->execute([$topicID, $postID, $user_class->id, $board['fb_id']]);
        $db->query('UPDATE users SET posts = posts + 1 WHERE id = ?');
        $db->execute([$user_class->id]);
        $db->trans('end');
        echo Message('<p>Your new topic has been created!</p>', 'Success');
        $_GET['viewtopic'] = $topicID;
        $_GET['latest'] = true;
        exit(nf_viewtopic($db, $user_class, $parser));
    }
    ?><form action="plugins/newforum.php?act=newtopic&amp;forum=<?php echo $board['fb_id']; ?>" method="post" class="pure-form pure-form-aligned">
        <?php echo csrf_create(); ?>
        <fieldset>
            <div class="pure-control-group">
                <label for="name">Topic Name</label>
                <input type="text" name="name" id="name" autofocus required />
            </div>
            <div class="pure-control-group">
                <label for="message">Topic Message</label>
                <textarea name="message" id="message" rows="7" cols="40"></textarea>
            </div>
            <div class="pure-controls">
                <button type="submit" name="submit" class="pure-button pure-button-primary">Create New Topic</button>
            </div>
        </fieldset>
    </form><?php
}

// ─────────────────────────────────────────────────────────────────────────────
// REPLY TO TOPIC
// ─────────────────────────────────────────────────────────────────────────────
function nf_reply($db, $user_class, $parser): void
{
    if (!csrf_check('csrf', $_POST)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if (empty($_GET['reply'])) {
        echo Message('Well.. Something screwed up.. Please try again later', 'Error', true);
    }
    $db->query('SELECT ft_id, ft_name, ft_locked, ft_board FROM forum_topics WHERE ft_id = ?');
    $db->execute([$_GET['reply']]);
    if (!$db->count()) {
        echo Message('That topic doesn\'t exist', 'Error', true);
    }
    $topic = $db->fetch(true);

    $db->query('SELECT fb_id, fb_owner, fb_auth FROM forum_boards WHERE fb_id = ?');
    $db->execute([$topic['ft_board']]);
    if (!$db->count()) {
        echo Message('The board for this topic doesn\'t exist'.(nf_trashTopic($topic['ft_id']) ? '. This topic has been automatically deleted/recycled' : ''), 'Error', true);
    }
    $board = $db->fetch(true);
    nf_accessCheck($board, $user_class);

    if ($topic['ft_locked'] && $user_class->admin != 1) {
        echo Message('This topic has been locked. No further responses are permitted', 'Error', true);
    }
    $_POST['message'] = array_key_exists('message', $_POST) && is_string($_POST['message']) ? strip_tags(trim($_POST['message'])) : null;
    if (empty($_POST['message'])) {
        echo Message('You didn\'t enter a valid response', 'Error', true);
    }

    // Notify subscribers
    $db->query('SELECT userid FROM forum_subscriptions WHERE topic = ?');
    $db->execute([$topic['ft_id']]);
    $notify = $db->fetch();
    $db->trans('start');
    if ($notify !== null) {
        foreach ($notify as $user) {
            if ($user['userid'] != $user_class->id) {
                Send_Event($user['userid'], '{extra} has posted on your subscription: <a href="plugins/newforum.php?viewtopic='.$topic['ft_id'].'&amp;latest">'.format($topic['ft_name']).'</a>', $user_class->id);
            }
        }
    }
    $db->query('INSERT INTO forum_posts (fp_board, fp_topic, fp_poster, fp_text) VALUES (?, ?, ?, ?)');
    $db->execute([$board['fb_id'], $topic['ft_id'], $user_class->id, $_POST['message']]);
    $post = $db->id();
    $db->query('UPDATE forum_topics SET ft_latest_time = NOW(), ft_latest_user = ?, ft_latest_post = ? WHERE ft_id = ?');
    $db->execute([$user_class->id, $post, $topic['ft_id']]);
    $db->query('UPDATE forum_boards SET fb_posts = fb_posts + 1, fb_latest_topic = ?, fb_latest_post = ?, fb_latest_poster = ?, fb_latest_time = NOW() WHERE fb_id = ?');
    $db->execute([$topic['ft_id'], $post, $user_class->id, $board['fb_id']]);
    $db->query('UPDATE users SET posts = posts + 1 WHERE id = ?');
    $db->execute([$user_class->id]);
    nf_tag($_POST['message'], false, $topic['ft_id']);
    $db->trans('end');
    echo Message('Your response has been posted', 'Success');
    $_GET['latest']    = true;
    $_GET['viewtopic'] = $_GET['reply'];
    nf_viewtopic($db, $user_class, $parser);
}

// ─────────────────────────────────────────────────────────────────────────────
// QUOTE A POST
// ─────────────────────────────────────────────────────────────────────────────
function nf_quote($db, $user_class, $parser): void
{
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if (empty($_GET['viewtopic'])) {
        echo Message('You didn\'t select a valid topic', 'Error', true);
    }
    $db->query('SELECT ft_id, ft_name, ft_board, ft_locked FROM forum_topics WHERE ft_id = ?');
    $db->execute([$_GET['viewtopic']]);
    if (!$db->count()) {
        echo Message('That topic doesn\'t exist', 'Error', true);
    }
    $topic = $db->fetch(true);

    $_GET['quote'] = array_key_exists('quote', $_GET) && ctype_digit($_GET['quote']) ? $_GET['quote'] : null;
    if (empty($_GET['quote'])) {
        echo Message('You didn\'t select a valid post to quote', 'Error', true);
    }
    $db->query('SELECT fp_id, fp_topic, fp_poster, fp_text FROM forum_posts WHERE fp_id = ?');
    $db->execute([$_GET['quote']]);
    if (!$db->count()) {
        echo Message('That post doesn\'t exist', 'Error', true);
    }
    $post = $db->fetch(true);

    if ($post['fp_topic'] != $topic['ft_id']) {
        echo Message('That post doesn\'t belong to '.format($topic['ft_name']), 'Error', true);
    }
    $quoter = new User($post['fp_poster']);

    $db->query('SELECT fb_id, fb_name, fb_auth, fb_owner FROM forum_boards WHERE fb_id = ?');
    $db->execute([$topic['ft_board']]);
    if (!$db->count()) {
        echo Message('The board for this topic doesn\'t exist'.(nf_trashTopic($topic['ft_id']) ? '. This topic has been automatically deleted/recycled' : ''), 'Error', true);
    }
    $board = $db->fetch(true);
    ?><div class="big">
        <a href="plugins/newforum.php">Index</a> &rarr;
        <a href="plugins/newforum.php?viewforum=<?php echo $board['fb_id']; ?>"><?php echo format($board['fb_name']); ?></a> &rarr;
        <a href="plugins/newforum.php?viewtopic=<?php echo $topic['ft_id']; ?>"><?php echo format($topic['ft_name']); ?></a> &rarr;
        Quote
    </div><br /><?php
    nf_accessCheck($board, $user_class);

    if ($topic['ft_locked'] && $user_class->admin != 1) {
        echo Message('This topic has been locked. No further responses are permitted', 'Error', true);
    }
    ?><form action="plugins/newforum.php?reply=<?php echo $topic['ft_id']; ?>&amp;csrfg=<?php echo csrf_create('csrfg', false); ?>" method="post" class="pure-form pure-form-aligned">
        <?php echo csrf_create(); ?>
        <div class="pure-control-group">
            <label for="message">Quote/Response</label>
            <textarea name="message" id="message" rows="7" cols="40" autofocus required>[quote=<?php echo $quoter->username; ?>]<?php echo format($post['fp_text']); ?>[/quote]</textarea>
        </div>
        <div class="pure-controls">
            <button type="submit" name="submit" class="pure-button pure-button-primary">Post Response</button>
        </div>
    </form><?php
}

// ─────────────────────────────────────────────────────────────────────────────
// EDIT A POST
// ─────────────────────────────────────────────────────────────────────────────
function nf_edit($db, $user_class, $parser): void
{
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if (empty($_GET['post']) || empty($_GET['topic'])) {
        echo Message('You didn\'t select a valid '.(empty($_GET['post']) ? 'post' : 'topic'), 'Error', true);
    }
    $db->query('SELECT ft_id, ft_name, ft_board FROM forum_topics WHERE ft_id = ?');
    $db->execute([$_GET['topic']]);
    if (!$db->count()) {
        echo Message('That topic doesn\'t exist', 'Error', true);
    }
    $topic = $db->fetch(true);

    $db->query('SELECT fb_id, fb_name, fb_auth, fb_owner FROM forum_boards WHERE fb_id = ?');
    $db->execute([$topic['ft_board']]);
    if (!$db->count()) {
        echo Message('The board for this topic doesn\'t exist'.(nf_trashTopic($topic['ft_id']) ? '. This topic has been automatically deleted/recycled' : ''), 'Error', true);
    }
    $board = $db->fetch(true);
    ?><div class="big">
        <a href="plugins/newforum.php">Index</a> &rarr;
        <a href="plugins/newforum.php?viewforum=<?php echo $board['fb_id']; ?>"><?php echo format($board['fb_name']); ?></a> &rarr;
        <a href="plugins/newforum.php?viewtopic=<?php echo $topic['ft_id']; ?>"><?php echo format($topic['ft_name']); ?></a> &rarr;
        Edit Post
    </div><br /><?php
    nf_accessCheck($board, $user_class);

    $db->query('SELECT fp_id, fp_topic, fp_poster, fp_text FROM forum_posts WHERE fp_id = ?');
    $db->execute([$_GET['post']]);
    if (!$db->count()) {
        echo Message('That post wasn\'t found', 'Error', true);
    }
    $post = $db->fetch(true);

    if ($post['fp_topic'] != $topic['ft_id']) {
        echo Message('That post doesn\'t belong to '.format($topic['ft_name']), 'Error', true);
    }
    if (!($user_class->admin == 1 || $user_class->id == $post['fp_poster'])) {
        echo Message('You don\'t have access', 'Error', true);
    }
    if (array_key_exists('submit', $_POST)) {
        if (!csrf_check('csrf', $_POST)) {
            echo Message(SECURITY_TIMEOUT_MESSAGE);
        }
        $_POST['message'] = array_key_exists('message', $_POST) && is_string($_POST['message']) ? strip_tags(trim($_POST['message'])) : null;
        $_POST['reason']  = array_key_exists('reason', $_POST)  && is_string($_POST['reason'])  ? strip_tags(trim($_POST['reason']))  : '';
        if (empty($_POST['message'])) {
            echo Message('You didn\'t enter a valid message', 'Error', true);
        }
        if ($_POST['message'] == $post['fp_text']) {
            echo Message('You didn\'t make any changes', 'Error', true);
        }
        $db->query('UPDATE forum_posts SET fp_text = ?, fp_edit_times = fp_edit_times + 1, fp_edit_reason = ?, fp_edit_time = NOW() WHERE fp_id = ?');
        $db->execute([$_POST['message'], $_POST['reason'], $_GET['post']]);
        echo Message('Your edit has been saved', 'Success');
        $_GET['viewtopic'] = $_GET['topic'];
        exit(nf_viewtopic($db, $user_class, $parser));
    }
    ?><form action="plugins/newforum.php?act=edit&amp;topic=<?php echo $topic['ft_id']; ?>&amp;post=<?php echo $post['fp_id']; ?>" method="post" class="pure-form">
        <?php echo csrf_create(); ?>
        <div class="pure-control-group">
            <label for="message">Post</label><br />
            <textarea name="message" id="message" rows="7" cols="40" autofocus><?php echo format($post['fp_text']); ?></textarea>
        </div>
        <div class="pure-control-group">
            <label for="reason">Reason for editing (optional)</label>
            <input type="text" name="reason" id="reason" />
        </div>
        <div class="pure-controls">
            <button type="submit" name="submit" class="pure-button pure-button-primary">Save Edit</button>
        </div>
    </form><?php
}

// ─────────────────────────────────────────────────────────────────────────────
// DELETE A POST
// ─────────────────────────────────────────────────────────────────────────────
function nf_delepost($db, $user_class, $parser): void
{
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if (empty($_GET['post'])) {
        echo Message('You didn\'t select a valid post', 'Error', true);
    }
    $db->query('SELECT fp_id, fp_topic, fp_board, fp_poster FROM forum_posts WHERE fp_id = ?');
    $db->execute([$_GET['post']]);
    if (!$db->count()) {
        echo Message('That post doesn\'t exist', 'Error', true);
    }
    $post = $db->fetch(true);

    if ($post['fp_poster'] != $user_class->id && $user_class->admin != 1) {
        echo Message('You don\'t have access', 'Error', true);
    }
    $db->query('SELECT ft_id, ft_name FROM forum_topics WHERE ft_id = ?');
    $db->execute([$post['fp_topic']]);
    if (!$db->count()) {
        echo Message('The parent topic for this post doesn\'t exist', 'Error', true);
    }
    $topic = $db->fetch(true);

    $db->trans('start');
    $db->query('DELETE FROM forum_posts WHERE fp_id = ?');
    $db->execute([$post['fp_id']]);
    nf_recache_topic($post['fp_topic']);
    nf_recache_forum($post['fp_board']);
    $db->trans('end');

    $_GET['viewtopic'] = $topic['ft_id'];
    echo Message('Post #'.format($post['fp_id']).' has been deleted', 'Success');
    nf_viewtopic($db, $user_class, $parser);
}

// ─────────────────────────────────────────────────────────────────────────────
// DELETE A TOPIC
// ─────────────────────────────────────────────────────────────────────────────
function nf_deletopic($db, $user_class, $parser): void
{
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if ($user_class->admin != 1) {
        echo Message('You don\'t have access', 'Error', true);
    }
    if (empty($_GET['topic'])) {
        echo Message('You didn\'t select a valid topic', 'Error', true);
    }
    $db->query('SELECT ft_id, ft_name, ft_board FROM forum_topics WHERE ft_id = ?');
    $db->execute([$_GET['topic']]);
    if (!$db->count()) {
        echo Message('That topic doesn\'t exist', 'Error', true);
    }
    $topic = $db->fetch(true);

    $db->query('SELECT fb_id FROM forum_boards WHERE fb_bin = 1');
    $db->execute();
    if ($db->count()) {
        $bin = $db->result();
        $db->trans('start');
        $db->query('UPDATE forum_topics SET ft_board = ? WHERE ft_id = ?');
        $db->execute([$bin, $topic['ft_id']]);
        $db->query('UPDATE forum_posts SET fp_board = ? WHERE fp_topic = ?');
        $db->execute([$bin, $topic['ft_id']]);
        nf_recache_forum($topic['ft_board']);
        $db->trans('end');
        echo Message(format($topic['ft_name']).' has been sent to the Recycle Bin', 'Success');
    } else {
        $db->trans('start');
        $db->query('DELETE FROM forum_topics WHERE ft_id = ?');
        $db->execute([$topic['ft_id']]);
        $db->query('DELETE FROM forum_posts WHERE fp_topic = ?');
        $db->execute([$topic['ft_id']]);
        nf_recache_forum($topic['ft_board']);
        $db->trans('end');
        echo Message(format($topic['ft_name']).' has been deleted', 'Success');
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// MOVE TOPIC
// ─────────────────────────────────────────────────────────────────────────────
function nf_move($db, $user_class, $parser): void
{
    if (!csrf_check('csrf', $_POST)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if ($user_class->admin != 1) {
        echo Message('You don\'t have access', 'Error', true);
    }
    if (empty($_GET['topic'])) {
        echo Message('You didn\'t select a valid topic', 'Error', true);
    }
    $db->query('SELECT ft_id, ft_name, ft_board FROM forum_topics WHERE ft_id = ?');
    $db->execute([$_GET['topic']]);
    if (!$db->count()) {
        echo Message('That topic doesn\'t exist', 'Error', true);
    }
    $topic = $db->fetch(true);

    $_POST['board'] = array_key_exists('board', $_POST) && ctype_digit($_POST['board']) ? $_POST['board'] : null;
    if (empty($_POST['board'])) {
        echo Message('You didn\'t select a valid board', 'Error', true);
    }
    if ($_POST['board'] == $topic['ft_board']) {
        echo Message('The destination board is the same as the original board', 'Error', true);
    }
    $db->query('SELECT fb_id, fb_name FROM forum_boards WHERE fb_id = ?');
    $db->execute([$_POST['board']]);
    if (!$db->count()) {
        echo Message('That board doesn\'t exist', 'Error', true);
    }
    $board = $db->fetch(true);

    $postCount = nf_getPostCount($topic['ft_id'], 'posts_topics');
    $db->trans('start');
    $db->query('UPDATE forum_posts SET fp_board = ? WHERE fp_topic = ?');
    $db->execute([$board['fb_id'], $topic['ft_id']]);
    $db->query('UPDATE forum_topics SET ft_board = ? WHERE ft_id = ?');
    $db->execute([$board['fb_id'], $topic['ft_id']]);
    $db->query('UPDATE forum_boards SET fb_posts = fb_posts + ?, fb_topics = fb_topics + 1 WHERE fb_id = ?');
    $db->execute([$postCount, $board['fb_id']]);
    nf_recache_forum($board['fb_id']);
    nf_recache_forum($topic['ft_board']);
    $db->trans('end');

    echo Message('You\'ve moved '.format($topic['ft_name']).' to '.format($board['fb_name']), 'Success');
    $_GET['viewtopic'] = $topic['ft_id'];
    nf_viewtopic($db, $user_class, $parser);
}

// ─────────────────────────────────────────────────────────────────────────────
// LOCK / UNLOCK TOPIC
// ─────────────────────────────────────────────────────────────────────────────
function nf_lock($db, $user_class, $parser): void
{
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if ($user_class->admin != 1) {
        echo Message('You don\'t have access', 'Error', true);
    }
    if (empty($_GET['topic'])) {
        echo Message('You didn\'t select a valid topic', 'Error', true);
    }
    $db->query('SELECT ft_id, ft_name, ft_locked, ft_board FROM forum_topics WHERE ft_id = ?');
    $db->execute([$_GET['topic']]);
    if (!$db->count()) {
        echo Message('That topic doesn\'t exist', 'Error', true);
    }
    $topic = $db->fetch(true);

    $opposite = $topic['ft_locked'] ? 'Unl' : 'L';
    $db->trans('start');
    $db->query('UPDATE forum_topics SET ft_locked = IF(ft_locked = 1, 0, 1) WHERE ft_id = ?');
    $db->execute([$topic['ft_id']]);
    $db->trans('end');

    echo Message('You\'ve '.strtolower($opposite).'ocked '.format($topic['ft_name']), 'Success');
    $_GET['viewforum'] = $topic['ft_board'];
    nf_viewforum($db, $user_class, $parser);
}

// ─────────────────────────────────────────────────────────────────────────────
// PIN / UNPIN TOPIC
// ─────────────────────────────────────────────────────────────────────────────
function nf_pin($db, $user_class, $parser): void
{
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if ($user_class->admin != 1) {
        echo Message('You don\'t have access', 'Error', true);
    }
    if (empty($_GET['topic'])) {
        echo Message('You didn\'t select a valid topic', 'Error', true);
    }
    $db->query('SELECT ft_id, ft_name, ft_pinned, ft_board FROM forum_topics WHERE ft_id = ?');
    $db->execute([$_GET['topic']]);
    if (!$db->count()) {
        echo Message('That topic doesn\'t exist', 'Error', true);
    }
    $topic = $db->fetch(true);

    $opposite = $topic['ft_pinned'] ? 'Unp' : 'P';
    $db->trans('start');
    $db->query('UPDATE forum_topics SET ft_pinned = IF(ft_pinned = 1, 0, 1) WHERE ft_id = ?');
    $db->execute([$topic['ft_id']]);
    $db->trans('end');

    echo Message('You\'ve '.strtolower($opposite).'inned '.format($topic['ft_name']), 'Success');
    $_GET['viewforum'] = $topic['ft_board'];
    nf_viewforum($db, $user_class, $parser);
}

// ─────────────────────────────────────────────────────────────────────────────
// SUBSCRIBE / UNSUBSCRIBE
// ─────────────────────────────────────────────────────────────────────────────
function nf_subscribe($db, $user_class, $parser): void
{
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if (empty($_GET['topic'])) {
        echo Message('You didn\'t select a valid topic', 'Error', true);
    }
    $db->query('SELECT ft_id, ft_name FROM forum_topics WHERE ft_id = ?');
    $db->execute([$_GET['topic']]);
    if (!$db->count()) {
        echo Message('That topic doesn\'t exist', 'Error', true);
    }
    $topic = $db->fetch(true);

    $db->query('SELECT id FROM forum_subscriptions WHERE userid = ? AND topic = ?');
    $db->execute([$user_class->id, $topic['ft_id']]);
    if ($db->count()) {
        $id = $db->result();
        $db->query('DELETE FROM forum_subscriptions WHERE id = ?');
        $db->execute([$id]);
        $which = 'unsubscribed from';
    } else {
        $db->query('INSERT INTO forum_subscriptions (userid, topic) VALUES (?, ?)');
        $db->execute([$user_class->id, $topic['ft_id']]);
        $which = 'subscribed to';
    }
    $_SESSION['success'] = 'You\'ve '.$which.' <a href="plugins/newforum.php?viewtopic='.$topic['ft_id'].'">'.format($topic['ft_name']).'</a>';
    if (array_key_exists('from', $_GET) && $_GET['from'] === 'manage') {
        exit(header('Location: newforum.php?act=managesub'));
    }
    $_GET['latest']    = true;
    $_GET['viewtopic'] = $topic['ft_id'];
    nf_viewtopic($db, $user_class, $parser);
}

// ─────────────────────────────────────────────────────────────────────────────
// MANAGE SUBSCRIPTIONS
// ─────────────────────────────────────────────────────────────────────────────
function nf_manage_subscriptions($db, $user_class, $parser): void
{
    $db->query('SELECT id, date_subbed, ft_id, ft_name, ft_latest_post, ft_latest_user, ft_latest_time
                FROM forum_subscriptions
                LEFT JOIN forum_topics ON topic = ft_id
                WHERE userid = ?
                ORDER BY date_subbed');
    $db->execute([$user_class->id]);
    $rows = $db->fetch();
    ?><table class="pure-table pure-table-horizontal" width="100%">
        <thead>
            <tr>
                <th width="35%">Topic</th>
                <th width="35%">Latest Poster</th>
                <th width="30%">Actions</th>
            </tr>
        </thead><?php
    if ($rows !== null) {
        $csrfg = csrf_create('csrfg', false);
        foreach ($rows as $row) {
            $poster = $row['ft_latest_user'] ? new User($row['ft_latest_user']) : (object)['formattedname' => 'No activity yet'];
            ?><tr>
                <td><a href="plugins/newforum.php?viewtopic=<?php echo $row['ft_id']; ?>"><?php echo format($row['ft_name']); ?></a></td>
                <td><?php echo $row['ft_latest_user'] ? $poster->formattedname : 'No activity yet'; ?></td>
                <td>
                    <a href="plugins/newforum.php?act=sub&amp;topic=<?php echo $row['ft_id']; ?>&amp;from=manage&amp;csrfg=<?php echo $csrfg; ?>" class="pure-button pure-button-grey">
                        Unsubscribe
                    </a>
                </td>
            </tr><?php
        }
    } else {
        ?><tr>
            <td colspan="3" class="center"><p>You don't have any subscriptions.</p></td>
        </tr><?php
    }
    ?></table><?php
}

// ─────────────────────────────────────────────────────────────────────────────
// HELPER FUNCTIONS
// ─────────────────────────────────────────────────────────────────────────────

/** Check whether a user is allowed to access a forum board. */
function nf_accessCheck(array $data, $user_class): void
{
    if (!isset($data['fb_auth'], $data['fb_owner'])) {
        echo Message('Resource not defined', 'Error', true);
    }
    if ($data['fb_auth'] === 'family' && $user_class->gang != $data['fb_owner'] && $user_class->admin != 1) {
        echo Message('You don\'t have access to this board', 'Error', true);
    }
    if ($data['fb_auth'] === 'staff' && $user_class->admin != 1) {
        echo Message('You don\'t have access to this board', 'Error', true);
    }
}

/** Return a count for a given forum resource. */
function nf_getPostCount($id = null, string $type = ''): int
{
    global $db;
    if (!ctype_digit((string)$id)) {
        return 0;
    }
    switch ($type) {
        case 'topics':
            $db->query('SELECT COUNT(ft_id) FROM forum_topics WHERE ft_board = ?');
            $db->execute([$id]);
            return (int)$db->result();
        case 'posts_boards':
            $db->query('SELECT COUNT(fp_id) FROM forum_posts WHERE fp_board = ?');
            $db->execute([$id]);
            return (int)$db->result();
        case 'posts_topics':
            $db->query('SELECT COUNT(fp_id) FROM forum_posts WHERE fp_topic = ?');
            $db->execute([$id]);
            return (int)$db->result();
        case 'posts_user':
            $db->query('SELECT COUNT(fp_id) FROM forum_posts WHERE fp_poster = ?');
            $db->execute([$id]);
            return (int)$db->result();
        default:
            return 0;
    }
}

/** Return a forum rank label based on total post count. */
function nf_forums_rank(int $posts = 0): string
{
    if ($posts >= 5000) {
        return '<span style="color:#FFD700;">Forum Legend</span>';
    }
    if ($posts >= 2000) {
        return '<span style="color:#FFA500;">Elite Member</span>';
    }
    if ($posts >= 1000) {
        return '<span style="color:#00BFFF;">Senior Member</span>';
    }
    if ($posts >= 500) {
        return '<span style="color:#7FFF00;">Active Member</span>';
    }
    if ($posts >= 100) {
        return '<span style="color:#FFFFFF;">Member</span>';
    }
    return '<span style="color:#AAAAAA;">Newbie</span>';
}

/** Recache a topic's latest post metadata. */
function nf_recache_topic(int $id = 0): bool
{
    global $db;
    if (!$id) {
        return false;
    }
    $db->query('SELECT COUNT(ft_id) FROM forum_topics WHERE ft_id = ?');
    $db->execute([$id]);
    if ((int)$db->result() > 0) {
        $db->query('SELECT fp_id, fp_poster, fp_time, fp_topic, fp_board FROM forum_posts WHERE fp_topic = ? ORDER BY fp_time DESC LIMIT 1');
        $db->execute([$id]);
        $post = $db->fetch(true);
        if ($post !== null) {
            $postCount  = nf_getPostCount((int)$post['fp_board'], 'posts_boards');
            $topicCount = nf_getPostCount((int)$post['fp_board'], 'topics');
            $db->query('UPDATE forum_boards SET fb_topics = ?, fb_posts = ?, fb_latest_topic = ?, fb_latest_post = ?, fb_latest_poster = ?, fb_latest_time = ? WHERE fb_id = ?');
            $db->execute([
                $topicCount, $postCount, $post['fp_topic'], $post['fp_id'], $post['fp_poster'], $post['fp_time'], $post['fp_board'],
            ]);
        } else {
            $db->query('UPDATE forum_topics SET ft_latest_time = NULL, ft_latest_user = 0, ft_latest_post = 0 WHERE ft_id = ?');
            $db->execute([$id]);
        }
    }
    return true;
}

/** Recache a board's latest post and counts. */
function nf_recache_forum(int $id = 0): bool
{
    global $db;
    if (!$id) {
        return false;
    }
    $db->query('SELECT fp_id, fp_poster, fp_time, ft_id, ft_name
                FROM forum_posts
                LEFT JOIN forum_topics ON fp_topic = ft_id
                WHERE fp_board = ?
                ORDER BY fp_time DESC LIMIT 1');
    $db->execute([$id]);
    if ($db->count()) {
        $row        = $db->fetch(true);
        $postCount  = nf_getPostCount($id, 'posts_boards');
        $topicCount = nf_getPostCount($id, 'topics');
        $db->query('UPDATE forum_boards SET fb_topics = ?, fb_posts = ?, fb_latest_topic = ?, fb_latest_post = ?, fb_latest_poster = ?, fb_latest_time = ? WHERE fb_id = ?');
        $db->execute([$topicCount, $postCount, $row['ft_id'], $row['fp_id'], $row['fp_poster'], $row['fp_time'], $id]);
    } else {
        $db->query('UPDATE forum_boards SET fb_topics = 0, fb_posts = 0, fb_latest_topic = 0, fb_latest_post = 0, fb_latest_poster = 0, fb_latest_time = NULL WHERE fb_id = ?');
        $db->execute([$id]);
    }
    return true;
}

/** Move a topic to the recycle bin (or delete it) if its parent board is missing. */
function nf_trashTopic(int $id = 0): bool
{
    global $db;
    if (!$id) {
        return false;
    }
    $db->query('SELECT ft_board FROM forum_topics WHERE ft_id = ?');
    $db->execute([$id]);
    if (!$db->count()) {
        return false;
    }
    $board = $db->result();
    $db->query('SELECT fb_id FROM forum_boards WHERE fb_bin = 1');
    $db->execute();
    if ($db->count()) {
        $bin = $db->result();
        $db->query('UPDATE forum_topics SET ft_board = ? WHERE ft_board = ?');
        $db->execute([$bin, $board]);
    } else {
        $db->query('DELETE FROM forum_topics WHERE ft_board = ?');
        $db->execute([$board]);
    }
    return true;
}

/**
 * Process @username tags in a forum post.
 * When $display is true, replaces tags with formatted names for output.
 * When $display is false, sends in-game events to tagged users.
 */
function nf_tag(string $text, bool $display = false, $id = false): ?string
{
    global $db;
    $cnt = 0;
    preg_match_all('/@(\w+)/', $text, $matches);
    $ids = [];
    if (count($matches) && count($matches[0])) {
        foreach ($matches[1] as $match) {
            ++$cnt;
            $db->query('SELECT id FROM users WHERE LOWER(username) = ? LIMIT 1');
            $db->execute([str_replace('__', ' ', strtolower($match))]);
            $row = $db->fetch(true);
            if ($row !== null) {
                ++$cnt;
                $tagged = new User($row['id']);
                if (!$display && !isset($ids[$row['id']]) && !isset($event_sent)) {
                    Send_Event($row['id'], 'You\'ve been tagged in the forum!<br /><a href="plugins/newforum.php?viewtopic='.$id.'">View it here</a>');
                    $event_sent = true;
                } else {
                    return preg_replace('/@(\w+)/', $tagged->formattedname, $text);
                }
                $ids[$row['id']] = true;
                if ($cnt >= 10) {
                    break;
                }
            } else {
                return $display ? $text : null;
            }
        }
    } else {
        return $display ? $text : null;
    }
    return $display ? $text : null;
}

if ($_GET['act'] !== 'viewtopic') {
    ?>  </td>
    </tr><?php
}
