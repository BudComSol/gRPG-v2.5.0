<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
if (!csrf_check('csrfg', $_GET)) {
    echo Message(SECURITY_TIMEOUT_MESSAGE);
}
$_GET['npc'] = array_key_exists('npc', $_GET) && ctype_digit($_GET['npc']) ? $_GET['npc'] : null;
$errors = [];
if ($user_class->energypercent < 25) {
    $errors[] = 'You need to have at least 25% of your energy if you want to attack someone';
}
if ($user_class->jail) {
    $errors[] = 'You can\'t attack someone whilst in jail';
}
if ($user_class->hospital) {
    $errors[] = 'You can\'t attack someone whilst in hospital';
}
if (empty($_GET['npc'])) {
    $errors[] = 'You didn\'t choose an NPC to attack';
}
if (!empty($_GET['npc'])) {
    // Regenerate HP if regen time has passed
    $db->query('UPDATE npcs SET hp = max_hp, last_defeated = 0 WHERE id = ? AND last_defeated > 0 AND (UNIX_TIMESTAMP() - last_defeated) >= hp_regen_time');
    $db->execute([(int)$_GET['npc']]);
    $db->query('SELECT id, name, description, image, strength, defense, speed, hp, max_hp, level, money, city, enabled, can_attack FROM npcs WHERE id = ?');
    $db->execute([(int)$_GET['npc']]);
    if (!$db->count()) {
        $errors[] = 'That NPC doesn\'t exist';
    }
    $npc = $db->fetch(true);
    if ($npc) {
        if (!$npc['enabled']) {
            $errors[] = 'That NPC is currently inactive';
        }
        if ($npc['city'] != $user_class->city) {
            $errors[] = 'You must be in the same city as the NPC you\'re attacking';
        }
        if ($npc['hp'] <= 0) {
            $errors[] = 'That NPC is already defeated and recovering';
        }
    }
}
if (count($errors)) {
    display_errors($errors, true);
}
$npc_name = htmlspecialchars($npc['name'], ENT_QUOTES, 'UTF-8');
?><tr>
    <th class="content-head">Fight: <?php echo $npc_name; ?></th>
</tr>
<tr>
    <td class="content">You are fighting <?php echo $npc_name; ?> (Level <?php echo (int)$npc['level']; ?>).</td>
</tr>
<tr>
    <td class="content"><?php
$yourhp  = $user_class->hp;
$theirhp = (int)$npc['hp'];
$wait    = $user_class->speed > $npc['speed'] ? 1 : 0;
$limit   = 50;
$turns   = 0;
$winner  = null;
while ($yourhp > 0 && $theirhp > 0) {
    ++$turns;
    $damage = $npc['strength'] - $user_class->moddeddefense;
    $damage = ($damage < 1) ? 1 : $damage;
    if ($wait == 0) {
        $yourhp -= $damage;
        echo $npc_name; ?> hit you for <?php echo $damage; ?> damage using their fists.<br /><?php
    } else {
        $wait = 0;
    }
    if ($yourhp > 0) {
        $damage = $user_class->moddedstrength - $npc['defense'];
        $damage = ($damage < 1) ? 1 : $damage;
        $theirhp -= $damage;
        ?> You hit <?php echo $npc_name; ?> for <?php echo $damage; ?> damage using your <?php echo $user_class->weaponname ? format($user_class->weaponname) : 'fists'; ?>.<br /><?php
    }
    if ($theirhp <= 0) { // player won
        $winner    = 'player';
        $theirhp   = 0;
        $moneywon  = (int)floor($npc['money'] / 4);
        $expwon    = max(0, 150 - (25 * ($user_class->level - $npc['level'])));
        $db->trans('start');
        $db->query('UPDATE users SET experience = experience + ?, money = money + ?, battlewon = battlewon + 1, battlemoney = battlemoney + ? WHERE id = ?');
        $db->execute([$expwon, $moneywon, $moneywon, $user_class->id]);
        $db->query('UPDATE npcs SET hp = 0, money = GREATEST(money - ?, 0), last_defeated = UNIX_TIMESTAMP() WHERE id = ?');
        $db->execute([$moneywon, (int)$npc['id']]);
        if ($user_class->gang != 0) {
            $db->query('UPDATE gangs SET experience = experience + ? WHERE id = ?');
            $db->execute([$expwon, $user_class->gang]);
        }
        $db->trans('end');
        echo Message('You defeated '.$npc_name.'! You gain '.prettynum($expwon).' exp and loot '.prettynum($moneywon, true));
        break;
    }
    if ($yourhp <= 0) { // NPC won
        $winner   = 'npc';
        $yourhp   = 0;
        $moneylost = (int)floor($user_class->money / 10);
        $db->trans('start');
        if ($npc['can_attack']) {
            // NPC is aggressive — hospitalizes the player and steals money
            $db->query('UPDATE users SET money = GREATEST(money - ?, 0), hwho = 0, hhow = \'npc_attacked\', hwhen = ?, hospital = 1200, battlelost = battlelost + 1, battlemoney = battlemoney - ? WHERE id = ?');
            $db->execute([$moneylost, date('g:i:sa'), $moneylost, $user_class->id]);
            $db->query('UPDATE npcs SET money = money + ? WHERE id = ?');
            $db->execute([$moneylost, (int)$npc['id']]);
            Send_Event($user_class->id, 'You were hospitalized by the NPC '.$npc_name.' for 20 minutes.', 0);
            $db->trans('end');
            echo Message($npc_name.' hospitalized you and stole '.prettynum($moneylost, true).' from you.');
        } else {
            // Non-lethal NPC — player loses the fight but is not hospitalized
            $db->query('UPDATE users SET battlelost = battlelost + 1 WHERE id = ?');
            $db->execute([$user_class->id]);
            $db->trans('end');
            echo Message($npc_name.' overpowered you and drove you off. Better luck next time!');
        }
        break;
    }
    if ($limit > 0 && $turns >= $limit) {
        echo Message('Neither side could gain the upper hand. The fight ended in a stalemate.');
        break;
    }
}
// Update HP values
$db->query('UPDATE users SET hp = ?, energy = GREATEST(energy - (energy * .1), 0) WHERE id = ?');
$db->execute([$yourhp, $user_class->id]);
if ($theirhp > 0) {
    $db->query('UPDATE npcs SET hp = ? WHERE id = ?');
    $db->execute([$theirhp, (int)$npc['id']]);
}
?></td>
</tr>
<tr>
    <td class="content"><a href="plugins/npcs.php" class="pure-button">&#8592; Back to NPCs</a></td>
</tr>
