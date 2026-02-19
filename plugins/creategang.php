<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
if ($user_class->gang) {
    echo Message('<p>You\'re already in a gang, numpty.</p>', 'Error', true);
}
$cost = 50000;
if (array_key_exists('create', $_POST)) { // if they are wanting to start a new gang
    if (!csrf_check('csrf', $_POST)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    $errors = [];
    if ($cost > 0 && $cost > $user_class->money) {
        $errors[] = 'You don\'t have enough money to start a gang, you need at least '.prettynum($cost, true);
    }
    $_POST['name'] = array_key_exists('name', $_POST) && is_string($_POST['name']) ? strip_tags(trim($_POST['name'])) : null;
    if (empty($_POST['name'])) {
        $errors[] = 'You didn\'t enter a valid name';
    }
    $nameLen = strlen($_POST['name']);
    if ($nameLen < 3 || $nameLen > 20) {
        $errors[] = 'Your gang\'s name must be between 3 and 20 characters';
    }
    $db->query('SELECT COUNT(id) FROM gangs WHERE name = ?');
    $db->execute([$_POST['name']]);
    if ($db->result()) {
        $errors[] = 'Another gang with that name already exists, choose another.';
    }
    $_POST['tag'] = array_key_exists('tag', $_POST) && is_string($_POST['tag']) ? strip_tags(trim($_POST['tag'])) : null;
    if (empty($_POST['tag'])) {
        $errors[] = 'You didn\'t enter a valid tag';
    }
    $tagLen = strlen($_POST['tag']);
    if ($tagLen < 1 || $tagLen > 3) {
        $errors[] = 'Your gang\'s tag must be between 1 and 3 characters long.';
    }
    $db->query('SELECT COUNT(id) FROM gangs WHERE tag = ?');
    $db->execute([$_POST['tag']]);
    if ($db->result()) {
        $errors[] = 'Another gang has already taken that tag, chose another.';
    }
    if (count($errors)) {
        display_errors($errors);
    } else {
        $db->trans('start');
        $db->query('INSERT INTO gangs (name, tag, leader) VALUES (?, ?, ?)');
        $db->execute([$_POST['name'], $_POST['tag'], $user_class->id]);
        $id = $db->id();
        $db->query('UPDATE users SET money = GREATEST(money - ?, 0), gang = ? WHERE id = ?');
        $db->execute([$cost, $id, $user_class->id]);
        $db->trans('end');
        echo Message('<p>Congrats, you\'ve created your new gang.</p>', 'Error', true);
    }
}
?><tr>
    <th class="content-head">Create Gang</th>
</tr>
<tr>
    <td class="content">
        <p>Well, it looks like you haven't join or created a gang yet.</p>
        <p>To create a gang is going to cost you $50,000.<br>If you don't have enough, or would like to join someone<br>elses gang, check out the <a href="plugins/gang_list.php">Gang List</a> for other gangs to join.</p>
        <form action="plugins/creategang.php" method="post" class="pure-form pure-form-aligned">
            <?php echo csrf_create(); ?>
            <fieldset>
                <div class="pure-control-group">
                    <label for="name">Gang Name</label>
                    <input type="text" name="name" id="name" maxlength="20" size="16" />
                </div>
                <div class="pure-control-group">
                    <label for="tag">Tag</label>
                    <input type="text" name="tag" id="tag" maxlength="3" size="4" />
                </div>
            </fieldset>
            <div class="pure-controls">
                <button type="submit" name="create" class="pure-button pure-button-primary">Create New Gang</button>
            </div>
        </form>
    </td>
</tr>
