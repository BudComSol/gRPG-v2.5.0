<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';

// Ensure the theatre_videos table exists
if (!$db->tableExists('theatre_videos')) {
    $db->query('CREATE TABLE IF NOT EXISTS `theatre_videos` (
        `id`         int(11)      NOT NULL PRIMARY KEY AUTO_INCREMENT,
        `user_id`    int(11)      NOT NULL,
        `title`      varchar(191) NOT NULL DEFAULT \'\',
        `youtube_id` varchar(20)  NOT NULL DEFAULT \'\',
        `added_at`   int(11)      NOT NULL DEFAULT 0,
        KEY (`user_id`),
        KEY (`added_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
    $db->execute();
}

/**
 * Extract a YouTube video ID from a URL.
 * Supports:
 *   https://www.youtube.com/watch?v=VIDEO_ID
 *   https://youtu.be/VIDEO_ID
 *   https://www.youtube.com/embed/VIDEO_ID
 *   https://www.youtube.com/shorts/VIDEO_ID
 *
 * @param string $url
 * @return string|null  The video ID (11 chars) or null if not found
 */
function extract_youtube_id(string $url): ?string
{
    $url = trim($url);
    $patterns = [
        '/(?:youtube\.com\/watch\?.*v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([A-Za-z0-9_\-]{11})/',
    ];
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $m)) {
            return $m[1];
        }
    }
    return null;
}

$errors  = [];
$section = isset($_GET['section']) && is_string($_GET['section']) ? $_GET['section'] : 'search';
$watch   = null;

// ── Watch a video ─────────────────────────────────────────────────────────────
if ($section === 'watch' && isset($_GET['id']) && ctype_digit((string)$_GET['id'])) {
    $db->query('SELECT tv.id, tv.title, tv.youtube_id, tv.user_id FROM theatre_videos tv WHERE tv.id = ?');
    $db->execute([(int)$_GET['id']]);
    $watch = $db->fetch(true);
    if ($watch === null) {
        echo Message('<p>That video does not exist.</p>', 'Error', true);
    }
}

// ── Add a video ───────────────────────────────────────────────────────────────
if (array_key_exists('add_video', $_POST)) {
    if (!csrf_check('theatre_csrf', $_POST)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE, null, true);
    }

    $_POST['title'] = array_key_exists('title', $_POST) && is_string($_POST['title'])
        ? trim($_POST['title']) : '';
    $_POST['url']   = array_key_exists('url', $_POST) && is_string($_POST['url'])
        ? trim($_POST['url']) : '';

    if (empty($_POST['title'])) {
        $errors[] = 'Please enter a title for the video.';
    } elseif (mb_strlen($_POST['title']) > 191) {
        $errors[] = 'Title must be 191 characters or fewer.';
    }

    $youtube_id = null;
    if (empty($_POST['url'])) {
        $errors[] = 'Please enter a YouTube URL.';
    } else {
        $youtube_id = extract_youtube_id($_POST['url']);
        if ($youtube_id === null) {
            $errors[] = 'That doesn\'t look like a valid YouTube URL. Please use a link like https://www.youtube.com/watch?v=xxxxx or https://youtu.be/xxxxx';
        }
    }

    if (!count($errors) && $youtube_id !== null) {
        // Prevent exact duplicate per user
        $db->query('SELECT COUNT(id) FROM theatre_videos WHERE user_id = ? AND youtube_id = ?');
        $db->execute([$user_class->id, $youtube_id]);
        if ((int)$db->result() > 0) {
            $errors[] = 'You already have that video in your collection.';
        }
    }

    if (!count($errors) && $youtube_id !== null) {
        $db->query('INSERT INTO theatre_videos (user_id, title, youtube_id, added_at) VALUES (?, ?, ?, ?)');
        $db->execute([$user_class->id, $_POST['title'], $youtube_id, time()]);
        echo Message('Video <strong>' . htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8') . '</strong> added to your collection!');
        $section = 'my_collection';
    }
}

// ── Delete a video ────────────────────────────────────────────────────────────
if (array_key_exists('delete', $_GET) && ctype_digit((string)$_GET['delete'])) {
    if (!csrf_check('theatre_del_csrf', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE, null, true);
    }
    $db->query('DELETE FROM theatre_videos WHERE id = ? AND user_id = ?');
    $db->execute([(int)$_GET['delete'], $user_class->id]);
    echo Message('Video removed from your collection.');
    $section = 'my_collection';
}

// ── Search videos ─────────────────────────────────────────────────────────────
$search_term = '';
$search_rows = null;
if ($section === 'search') {
    if (isset($_GET['q']) && is_string($_GET['q'])) {
        $search_term = trim($_GET['q']);
    }
    if ($search_term !== '') {
        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search_term);
        $like = '%' . $escaped . '%';
        $db->query('SELECT tv.id, tv.title, tv.youtube_id, tv.user_id FROM theatre_videos tv WHERE tv.title LIKE ? ORDER BY tv.added_at DESC LIMIT 50');
        $db->execute([$like]);
    } else {
        $db->query('SELECT tv.id, tv.title, tv.youtube_id, tv.user_id FROM theatre_videos tv ORDER BY tv.added_at DESC LIMIT 50');
        $db->execute();
    }
    $search_rows = $db->fetch();
}

// ── My collection ─────────────────────────────────────────────────────────────
$my_rows = null;
if ($section === 'my_collection') {
    $db->query('SELECT id, title, youtube_id, added_at FROM theatre_videos WHERE user_id = ? ORDER BY added_at DESC');
    $db->execute([$user_class->id]);
    $my_rows = $db->fetch();
}

$del_csrf = csrf_create('theatre_del_csrf', false);
?>
<tr>
    <th class="content-head">Movie Theatre</th>
</tr>
<tr>
    <td class="content">
        <div style="margin-bottom:10px;margin-top:10px;">
            <a class="pure-button<?php echo $section === 'search'        ? ' pure-button-primary' : ''; ?>" href="plugins/theatre.php?section=search">Browse Videos</a>
            <a class="pure-button<?php echo $section === 'add'           ? ' pure-button-primary' : ''; ?>" href="plugins/theatre.php?section=add">Add a Video</a>
            <a class="pure-button<?php echo $section === 'my_collection' ? ' pure-button-primary' : ''; ?>" href="plugins/theatre.php?section=my_collection">My Collection</a>
        </div>
    </td>
</tr>

<?php if ($watch !== null): ?>
<tr>
    <th class="content-head">▶ Now Playing: <?php echo htmlspecialchars($watch['title'], ENT_QUOTES, 'UTF-8'); ?></th>
</tr>
<tr>
    <td class="content" style="text-align:center;">
        <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;max-width:100%;">
            <iframe
                src="https://www.youtube-nocookie.com/embed/<?php echo htmlspecialchars($watch['youtube_id'], ENT_QUOTES, 'UTF-8'); ?>?rel=0"
                style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;"
                allowfullscreen
                loading="lazy"
                title="<?php echo htmlspecialchars($watch['title'], ENT_QUOTES, 'UTF-8'); ?>"
            ></iframe>
        </div>
        <p style="margin-top:8px;"><a href="plugins/theatre.php?section=search">Back to Browse</a></p>
    </td>
</tr>

<?php elseif ($section === 'search'): ?>
<tr>
    <th class="content-head">Browse Videos</th>
</tr>
<tr>
    <td class="content">
        <form action="plugins/theatre.php" method="get" class="pure-form-theatre">
            <input type="hidden" name="section" value="search" />
            <input type="text" name="q" value="<?php echo htmlspecialchars($search_term, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Search by title…" style="width:70%;height:25px;" />
            <button type="submit" class="pure-button pure-button-primary">Search</button>
        </form>
        <br />
        <?php if ($search_rows !== null && count($search_rows) > 0): ?>
        <table width="100%" class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>Added By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($search_rows as $row): ?>
                <tr>
                    <td style="width:120px;">
                        <a href="plugins/theatre.php?section=watch&id=<?php echo (int)$row['id']; ?>">
                            <img
                                src="https://img.youtube.com/vi/<?php echo htmlspecialchars($row['youtube_id'], ENT_QUOTES, 'UTF-8'); ?>/mqdefault.jpg"
                                alt="<?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                style="width:120px;height:68px;object-fit:cover;border-radius:4px;"
                                loading="lazy"
                            />
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php $poster = new User((int)$row['user_id']); echo $poster->formattedname; ?></td>
                    <td><a class="pure-button" href="plugins/theatre.php?section=watch&id=<?php echo (int)$row['id']; ?>">▶ Watch</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php elseif ($search_term !== ''): ?>
            <p>No videos found matching <em><?php echo htmlspecialchars($search_term, ENT_QUOTES, 'UTF-8'); ?></em>.</p>
        <?php else: ?>
            <p>No videos have been added yet. Be the first to <a href="plugins/theatre.php?section=add">add one</a>!</p>
        <?php endif; ?>
    </td>
</tr>

<?php elseif ($section === 'add'): ?>
<tr>
    <th class="content-head">Add a Video</th>
</tr>
<tr>
    <td class="content">
        <?php if (count($errors)): display_errors($errors); endif; ?>
        <p>Paste a YouTube link to add a video to the community theatre. Supported formats:</p>
        <ul>
            <li><code>https://www.youtube.com/watch?v=VIDEO_ID</code></li>
            <li><code>https://youtu.be/VIDEO_ID</code></li>
            <li><code>https://www.youtube.com/shorts/VIDEO_ID</code></li>
        </ul>
        <form action="plugins/theatre.php" method="post" class="pure-form pure-form-aligned">
            <?php echo csrf_create('theatre_csrf'); ?>
            <input type="hidden" name="section" value="add" />
            <div class="pure-control-group">
                <label for="vid_title">Title</label>
                <input type="text" name="title" id="vid_title" maxlength="191" style="width:300px;"
                    value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
            </div>
            <div class="pure-control-group">
                <label for="vid_url">YouTube URL</label>
                <input type="text" name="url" id="vid_url" style="width:300px;"
                    value="<?php echo isset($_POST['url']) ? htmlspecialchars($_POST['url'], ENT_QUOTES, 'UTF-8') : ''; ?>" />
            </div>
            <div class="pure-controls">
                <button type="submit" name="add_video" class="pure-button pure-button-primary">Add to Collection</button>
            </div>
        </form>
    </td>
</tr>

<?php elseif ($section === 'my_collection'): ?>
<tr>
    <th class="content-head">My Collection</th>
</tr>
<tr>
    <td class="content">
        <?php if ($my_rows !== null && count($my_rows) > 0): ?>
        <table width="100%" class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <th>Thumbnail</th>
                    <th>Title</th>
                    <th>Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($my_rows as $row): ?>
                <tr>
                    <td style="width:120px;">
                        <a href="plugins/theatre.php?section=watch&id=<?php echo (int)$row['id']; ?>">
                            <img
                                src="https://img.youtube.com/vi/<?php echo htmlspecialchars($row['youtube_id'], ENT_QUOTES, 'UTF-8'); ?>/mqdefault.jpg"
                                alt="<?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?>"
                                style="width:120px;height:68px;object-fit:cover;border-radius:4px;"
                                loading="lazy"
                            />
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo date('d M Y', (int)$row['added_at']); ?></td>
                    <td>
                        <a class="pure-button" href="plugins/theatre.php?section=watch&id=<?php echo (int)$row['id']; ?>">▶ Watch</a>
                        <a class="pure-button pure-button-warning" href="plugins/theatre.php?delete=<?php echo (int)$row['id']; ?>&theatre_del_csrf=<?php echo $del_csrf; ?>"
                            onclick="return confirm('Remove this video from your collection?');">✕ Remove</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p>Your collection is empty. <a href="plugins/theatre.php?section=add">Add your first video!</a></p>
        <?php endif; ?>
    </td>
</tr>
<?php endif; ?>
