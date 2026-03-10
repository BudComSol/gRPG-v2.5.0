<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
if (!csrf_check('csrfg', $_GET)) {
    echo Message(SECURITY_TIMEOUT_MESSAGE);
}
$_GET['npc'] = array_key_exists('npc', $_GET) && ctype_digit($_GET['npc']) ? $_GET['npc'] : null;
$errors = [];
if (empty($_GET['npc'])) {
    $errors[] = 'You didn\'t specify a valid NPC';
}
if ($user_class->jail) {
    $errors[] = 'You can\'t mug someone whilst in jail';
}
if ($user_class->hospital) {
    $errors[] = 'You can\'t mug someone whilst in hospital';
}
if ($user_class->nerve < 10) {
    $errors[] = 'You need at least 10 nerve to mug someone';
}
if (!empty($_GET['npc'])) {
    // Regenerate HP if regen time has passed
    $db->query('UPDATE npcs SET hp = max_hp, last_defeated = 0 WHERE id = ? AND last_defeated > 0 AND (UNIX_TIMESTAMP() - last_defeated) >= hp_regen_time');
    $db->execute([(int)$_GET['npc']]);
    $db->query('SELECT id, name, speed, money, hp, city, enabled, can_attack, level FROM npcs WHERE id = ?');
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
            $errors[] = 'You must be in the same city as the NPC you\'re trying to mug';
        }
        if ($npc['hp'] <= 0) {
            $errors[] = 'That NPC has already been defeated — there\'s nothing left to take';
        }
    }
}
if (count($errors)) {
    display_errors($errors);
} else {
    $npc_name = htmlspecialchars($npc['name'], ENT_QUOTES, 'UTF-8');
    if ($user_class->speed > $npc['speed']) {
        $mugamount = (int)floor($npc['money'] / 4);
        if ($user_class->class === 'Thief') {
            $mugamount = (int)ceil($mugamount * 1.05);
        }
        $db->trans('start');
        $db->query('UPDATE users SET money = money + ? WHERE id = ?');
        $db->execute([$mugamount, $user_class->id]);
        $db->query('UPDATE npcs SET money = GREATEST(money - ?, 0) WHERE id = ?');
        $db->execute([$mugamount, (int)$npc['id']]);
        $db->trans('end');
        echo Message('You mugged '.$npc_name.' for '.prettynum($mugamount, true));
    } else {
        // NPC is faster — if aggressive, it counter-mugs the player
        if ($npc['can_attack']) {
            $countermug = (int)floor($user_class->money / 10);
            $db->trans('start');
            $db->query('UPDATE users SET money = GREATEST(money - ?, 0) WHERE id = ?');
            $db->execute([$countermug, $user_class->id]);
            $db->query('UPDATE npcs SET money = money + ? WHERE id = ?');
            $db->execute([$countermug, (int)$npc['id']]);
            $db->trans('end');
            echo Message($npc_name.' spotted you and turned the tables — they mugged YOU for '.prettynum($countermug, true).'!');
        } else {
            echo Message($npc_name.'\'s speed is higher than yours. They saw you coming and you failed.');
        }
    }
    $db->query('UPDATE users SET nerve = GREATEST(nerve - 10, 0) WHERE id = ?');
    $db->execute([$user_class->id]);
}
?><tr>
    <td class="content"><a href="plugins/npcs.php" class="pure-button">&#8592; Back to NPCs</a></td>
</tr>
