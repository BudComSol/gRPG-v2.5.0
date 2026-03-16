<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';

$db->query('SELECT gamble_daily FROM users WHERE id = ?', [$user_class->id]);
$gamble_daily = (int)$db->result();

if ($gamble_daily > 0) {
    echo Message('<p>You can only have one go per day, come back tomorrow.</p>', null, true);
}
if ($user_class->jail > 0) {
    echo Message('<p>You cannot gamble, you are in the cells.</p>', null, true);
}

$allowed_bets = [500, 1000, 2500, 50000, 500000, 5000000];
$bet_odds = [
    500     => [1, 3],
    1000    => [1, 3],
    2500    => [1, 4],
    50000   => [1, 5],
    500000  => [1, 5],
    5000000 => [1, 5],
];

$bet = null;
if (array_key_exists('bet', $_GET)) {
    $bet_val = filter_var($_GET['bet'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($bet_val !== false && in_array($bet_val, $allowed_bets, true)) {
        $bet = $bet_val;
    }
}

if ($bet !== null) {
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE, null, true);
    }
    if ($user_class->money < $bet) {
        echo Message('<p>You need '.prettynum($bet, true).' in your hand to be able to try this gamble.</p>', null, true);
    }
    [$win_value, $range] = $bet_odds[$bet];
    $chance = mt_rand(1, $range);
    $time = time();
    $db->trans('start');
    if ($chance === $win_value) {
        $db->query('UPDATE users SET money = money + ?, gamble_daily = 1 WHERE id = ?');
        $db->execute([$bet, $user_class->id]);
        $db->query('INSERT INTO foolsgamble_log (userid, timestamp, text) VALUES (?, ?, ?)');
        $db->execute([$user_class->id, $time, 'Won '.prettynum($bet)]);
        $db->trans('end');
        echo Message('<p>Well done, you have won '.prettynum($bet, true).'!</p>', null, true);
    } else {
        $db->query('UPDATE users SET money = GREATEST(money - ?, 0), gamble_daily = 1 WHERE id = ?');
        $db->execute([$bet, $user_class->id]);
        $db->query('INSERT INTO foolsgamble_log (userid, timestamp, text) VALUES (?, ?, ?)');
        $db->execute([$user_class->id, $time, 'Lost '.prettynum($bet)]);
        $db->trans('end');
        echo Message('<p>Gutted ... you just lost '.prettynum($bet, true).'.</p>', null, true);
    }
}

$csrfg = csrf_create('csrfg', false);
?><tr>
    <th class="content-head">Fools Gamble</th>
</tr>
<tr>
    <td class="content">
        <p><img src="images/bigcash.png" alt="bigcash"/></p>
        <p>Are you a lucky person? You can take one of these bets daily.<br/>
        Make your choice and have the cash in your hand.<br/>
        If you win you double your stake, if you lose you may cry.</p>
        <p>
            <?php foreach ($allowed_bets as $b) { ?>
            <a href="plugins/foolsgamble.php?bet=<?php echo $b; ?>&amp;csrfg=<?php echo $csrfg; ?>" class="pure-button"><?php echo prettynum($b, true); ?></a>
            <?php } ?>
        </p>
        <p><a href="plugins/city.php">No thanks, I'm keeping my cash.</a></p>
    </td>
</tr>
<tr>
    <th class="content-head">Last 10 Fools Gamble Results</th>
</tr>
<tr>
    <td class="content">
        <table width="100%" class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <th>Thug</th>
                    <th>Date</th>
                    <th>Outcome</th>
                </tr>
            </thead><?php
$db->query('SELECT userid, timestamp, text FROM foolsgamble_log ORDER BY timestamp DESC LIMIT 10');
$db->execute();
$log_rows = $db->fetch();
if ($log_rows !== null) {
    foreach ($log_rows as $log_row) {
        $log_user = new User($log_row['userid']); ?>
        <tr>
            <td><?php echo $log_user->formattedname; ?></td>
            <td><?php echo date('M d, Y g:i:sa', (int)$log_row['timestamp']); ?></td>
            <td><?php echo htmlspecialchars($log_row['text'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr><?php
    }
} else { ?>
        <tr>
            <td colspan="3" class="center"><p>No gambles have been placed yet.</p></td>
        </tr><?php
} ?>
        </table>
    </td>
</tr>

