<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
if (!csrf_check('csrfg', $_GET)) {
    echo Message(SECURITY_TIMEOUT_MESSAGE);
    exit;
}
$_GET['fight'] = array_key_exists('fight', $_GET) && ctype_digit($_GET['fight']) ? $_GET['fight'] : null;
$errors = [];
if ($user_class->energypercent < 25) {
    $errors[] = 'You need to have at least 25% of your energy if you want to cage fight someone';
}
if ($user_class->jail) {
    $errors[] = 'You can\'t cage fight whilst in jail';
}
if ($user_class->hospital) {
    $errors[] = 'You can\'t cage fight whilst in hospital';
}
if (empty($_GET['fight'])) {
    $errors[] = 'You didn\'t choose someone to fight';
}
if (!empty($_GET['fight']) && $_GET['fight'] == $user_class->id) {
    $errors[] = 'You can\'t fight yourself';
}
if (!empty($_GET['fight']) && !userExists($_GET['fight'])) {
    $errors[] = 'That person doesn\'t exist';
}
$defender = !empty($_GET['fight']) && userExists($_GET['fight']) ? new User($_GET['fight']) : null;
if ($defender !== null) {
    if ($defender->city != $user_class->city) {
        $errors[] = 'You must be in the same city as the person you\'re fighting';
    }
    if ($defender->hospital) {
        $errors[] = 'You can\'t fight someone whilst they\'re in hospital';
    }
    if ($defender->jail) {
        $errors[] = 'You can\'t fight someone whilst they\'re in jail';
    }
    if ($user_class->level > 5 && $defender->level < 6) {
        $errors[] = 'You can\'t fight someone that is level 5 or below because you are higher than level 5.';
    }
}
if (count($errors)) {
    display_errors($errors, true);
}
// $defender is guaranteed non-null here: display_errors exits when errors exist,
// and $defender is only null when validation errors were added above.
$yourhp = $user_class->hp;
$theirhp = $defender->hp;
?><tr>
    <th class="content-head">Cage Fight</th>
</tr>
<tr>
    <td class="content">You are in a cage fight with <?php echo $defender->formattedname; ?>.</td>
</tr>
<tr>
    <td class="content"><?php
$wait = $user_class->speed > $defender->speed ? 1 : 0;
$limit = 50;
$turns = 0;
$winner = null;
while ($yourhp > 0 && $theirhp > 0) {
    ++$turns;
    $damage = $defender->moddedstrength - $user_class->moddeddefense;
    $damage = ($damage < 1) ? 1 : $damage;
    if ($wait == 0) {
        $yourhp -= $damage;
        echo $defender->formattedname; ?> hit you for <?php echo $damage; ?> damage using their <?php echo $defender->weaponname ? format($defender->weaponname) : 'fists'; ?>.<br /><?php
    } else {
        $wait = 0;
    }
    if ($yourhp > 0) {
        $damage = $user_class->moddedstrength - $defender->moddeddefense;
        $damage = ($damage < 1) ? 1 : $damage;
        $theirhp -= $damage; ?>You hit <?php echo $defender->formattedname; ?> for <?php echo $damage; ?> damage using your <?php echo $user_class->weaponname ? format($user_class->weaponname) : 'fists'; ?>.<br /><?php
    }
    if ($theirhp <= 0) { // attacker won
        $winner = $user_class->id;
        $theirhp = 0;
        $moneywon = floor($defender->money / 10);
        $expwon = 150 - (25 * ($user_class->level - $defender->level));
        $expwon = ($expwon < 0) ? 0 : $expwon;
        $db->trans('start');
        $db->query('UPDATE users SET experience = experience + ?, money = money + ?, battlewon = battlewon + 1, battlemoney = battlemoney + ? WHERE id = ?');
        $db->execute([$expwon, $moneywon, $moneywon, $user_class->id]);
        $db->query('UPDATE users SET money = GREATEST(money - ?, 0), hwho = ?, hhow = \'cagefight\', hwhen = ?, hospital = 1200, battlelost = battlelost + 1, battlemoney = battlemoney - ? WHERE id = ?');
        $db->execute([$moneywon, $user_class->id, date('g:i:sa'), $moneywon, $defender->id]);
        Send_Event($defender->id, 'You were knocked out in a cage fight by {extra} for 20 minutes.', $user_class->id);
        if ($user_class->gang != 0) {
            $db->query('UPDATE gangs SET experience = experience + ? WHERE id = ?');
            $db->execute([$expwon, $user_class->gang]);
        }
        $db->trans('end');
        echo Message('You knocked out '.$defender->formattedname.'! You gain '.prettynum($expwon).' exp and won '.prettynum($moneywon, true));
        break;
    }
    if ($yourhp <= 0) { // defender won
        $winner = $defender->id;
        $yourhp = 0;
        $moneywon = floor($user_class->money / 10);
        $expwon = 100 - (25 * ($defender->level - $user_class->level));
        $expwon = ($expwon < 0) ? 0 : $expwon;
        $db->trans('start');
        $db->query('UPDATE users SET experience = experience + ?, money = money + ?, battlewon = battlewon + 1, battlemoney = battlemoney + ? WHERE id = ?');
        $db->execute([$expwon, $moneywon, $moneywon, $defender->id]);
        $db->query('UPDATE users SET money = GREATEST(money - ?, 0), hwho = ?, hhow = \'cagefight\', hwhen = ?, hospital = 1200, battlelost = battlelost + 1, battlemoney = battlemoney - ? WHERE id = ?');
        $db->execute([$moneywon, $defender->id, date('g:i:sa'), $moneywon, $user_class->id]);
        Send_Event($user_class->id, 'You were knocked out in a cage fight by {extra} for 20 minutes.', $defender->id);
        if ($defender->gang != 0) {
            $db->query('UPDATE gangs SET experience = experience + ? WHERE id = ?');
            $db->execute([$expwon, $defender->gang]);
        }
        $db->trans('end');
        echo Message($defender->formattedname.' knocked you out and won '.prettynum($moneywon, true).' from you.');
        break;
    }
    if ($limit > 0 && $turns >= $limit) {
        echo Message('Neither of you could do enough damage to one another. This ended in a stalemate.');
        break;
    }
}
if (isset($winner) && $defender->gang != 0) {
    $db->query('INSERT INTO ganglog (gangid, attacker, defender, winner) VALUES (?, ?, ?, ?)');
    $db->execute([$defender->gang, $user_class->id, $defender->id, $winner]);
}
$db->query('UPDATE users SET hp = ? WHERE id = ?');
$db->execute([$theirhp, $defender->id]);
$db->query('UPDATE users SET hp = ?, energy = GREATEST(energy - (energy * .1), 0) WHERE id = ?');
$db->execute([$yourhp, $user_class->id]);
?></td>
</tr>
<tr>
    <td class="content"><a href="plugins/citizens.php" class="pure-button">&#8592; Back to Citizens</a></td>
</tr>
