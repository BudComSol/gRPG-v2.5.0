<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
$errors = [];
?><tr>
    <th class="content-head">Job Center</th>
</tr><?php
$_GET['take'] = array_key_exists('take', $_GET) && ctype_digit($_GET['take']) ? $_GET['take'] : null;
if (!empty($_GET['action']) && $_GET['action'] === 'quit') {
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if (!$user_class->job) {
        $errors[] = 'You don\'t have a job to quit';
    }
    if (!count($errors)) {
        $db->query('UPDATE users SET job = 0 WHERE id = ?');
        $db->execute([$user_class->id]);
        $user_class = new User($user_class->id);
        echo Message('You\'ve quit your job.');
    }
}
if (!empty($_GET['take'])) {
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if ($user_class->job) {
        $errors[] = 'You already have a job, numpty.';
    }
    $db->query('SELECT * FROM jobs WHERE id = ?');
    $db->execute([$_GET['take']]);
    if (!$db->count()) {
        $errors[] = 'The job you selected doesn\'t exist';
    }
    $row = $db->fetch(true);
    if ($row['level'] > $user_class->level) {
        $errors[] = 'You\'re not experienced enough to take this job';
    }
    if ($row['strength'] > $user_class->strength) {
        $errors[] = 'You\'re not strong enough to work here';
    }
    if ($row['defense'] > $user_class->defense) {
        $errors[] = 'Your guard isn\'t good enough for this job';
    }
    if ($row['speed'] > $user_class->speed) {
        $errors[] = 'You\'re simply not fast enough';
    }
    if (!count($errors)) {
        $db->query('UPDATE users SET job = ? WHERE id = ?');
        $db->execute([$_GET['take'], $user_class->id]);
        echo Message('You\'ve taken the job: '.format($row['name']));
        $user_class = new User($user_class->id);
    }
}
$csrfg = csrf_create('csrfg', false);
if ($user_class->job) {
    $db->query('SELECT id, name, money FROM jobs WHERE id = ?');
    $db->execute([$user_class->job]);
    if (!$db->count()) {
        $db->query('UPDATE users SET job = 0 WHERE job = ?');
        $db->execute([$user_class->job]);
        echo Message('You\'ve just been made redundant..', 'Error', true);
    }
    $row = $db->fetch(true); ?>
    <tr>
        <td class="content">
            <p>You're currently an <?php echo format($row['name']); ?>
            and making <?php echo prettynum($row['money'], true); ?> a day.</p>
            <a href="plugins/jobs.php?action=quit&amp;csrfg=<?php echo $csrfg; ?>"><p>Quit This Job</p></a>
        </td>
      </tr>
    <th class="content-head">Available Jobs</th></tr>
    <?php
}
$db->query('SELECT * FROM jobs ORDER BY money ');
$db->execute();
$rows = $db->fetch();
if (count($errors)) {
    display_errors($errors);
}
?><tr>
    <td class="content">
        <table width="100%">
            <tr>
                <th width="25%">Job</th>
                <th width="35%">Requirements</th>
                <th width="20%">Daily Payment</th>
                <th width="20%">Apply For Job</th>
            </tr><?php
if ($rows !== null) {
        foreach ($rows as $row) {
            ?><tr>
                    <td><?php echo format($row['name']); ?></td>
                    <td>
                        Strength: <?php echo format($row['strength']); ?><br />
                        Defense: <?php echo format($row['defense']); ?><br />
                        Speed: <?php echo format($row['speed']); ?><br />
                        Level: <?php echo format($row['level']); ?>
                    </td>
                    <td><?php echo prettynum($row['money'], true); ?></td>
                    <td><?php
        if ($row['id'] > $user_class->job) {
            ?><a href="plugins/jobs.php?take=<?php echo $row['id']; ?>&amp;csrfg=<?php echo $csrfg; ?>">Take This Job</a><?php
        } elseif ($row['id'] == $user_class->job) {
            ?><span class="green italic">Working Here</span><?php
        } ?></td>
                </tr><?php
        }
    } else {
        ?><tr>
                    <td colspan="4" class="center">There are no jobs going</td>
                </tr><?php
    }
?></table>
    </td>
</tr>
