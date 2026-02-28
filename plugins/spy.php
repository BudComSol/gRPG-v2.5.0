<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
if (!csrf_check('csrfg', $_GET)) {
    echo Message(SECURITY_TIMEOUT_MESSAGE);
}
?><tr>
    <th class="content-head">Spying</th>
</tr>
<tr>
    <td class="content"><?php
if (empty($_GET['id'])) {
    echo Message('You didn\'t select a valid target', 'Error', true);
}
if ($_GET['id'] == $user_class->id) {
    echo Message('You can\'t spy on yourself', 'Error', true);
}
if (!userExists($_GET['id'])) {
    echo Message('The target you selected doesn\'t exist', 'Error', true);
}
$spy_class = new User($_GET['id']);
$cost = $user_class->level * 1000;
if (array_key_exists('confirm', $_GET)) {
        if ($cost > $user_class->money) {
            echo Message('<p>You don\'t have enough money for this, numpty.</p>', 'Error', true);
        }
        $points = mt_rand(0, 1) == 1 ? format($spy_class->points) : '<p>Your Private Investigator was unable to find information on their points.</p>';
        $bank = mt_rand(0, 1) == 1 ? format($spy_class->bank) : '<p>Your Private Investigator was unable to find information on their bank.</p>';
        $strength = mt_rand(0, 1) == 1 ? format($spy_class->strength) : '<p>Your Private Investigator was unable to find information on their strength.</p>';
        $defense = mt_rand(0, 1) == 1 ? format($spy_class->defense) : '<p>Your Private Investigator was unable to find information on their defense.</p>';
        $speed = mt_rand(0, 1) == 1 ? format($spy_class->speed) : '<p>Your Private Investigator was unable to find information on their speed.</p>';
        $db->trans('start');
        $db->query('UPDATE users SET money = GREATEST(money - ?, 0) WHERE id = ?');
        $db->execute([$cost, $user_class->id]);
        $db->query('INSERT INTO spylog (id, spyid, strength, defense, speed, bank, points, age) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $db->execute([$user_class->id, $spy_class->id, $strength, $defense, $speed, $bank, $points, time()]);
        $db->trans('end'); ?><p>Your Private Investigator found out the following about <?php echo $spy_class->formattedname; ?></p>
            <strong>Points:</strong> <?php echo $points; ?><br />
            <strong>Bank:</strong> <?php echo $bank; ?><br />
            <strong>Strength:</strong> <?php echo $strength; ?><br />
            <strong>Defense:</strong> <?php echo $defense; ?><br />
            <strong>Speed:</strong> <?php echo $speed;
    } else {
        ?><p>Are you sure that you want to hire a Private Investigator to spy on <?php echo $spy_class->formattedname; ?> for <?php echo prettynum($cost, true); ?>?</p>
                <a href="plugins/spy.php?id=<?php echo $spy_class->id; ?>&amp;confirm&amp;csrfg=<?php echo csrf_create('csrfg', false); ?>">Yes</a> | <a href="plugins/profiles.php?id=<?php echo $spy_class->id; ?>">No</a><?php
    }
?></td>
</tr>
