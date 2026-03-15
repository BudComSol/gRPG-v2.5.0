<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
checkUserStatus();
if (!$user_class->gang) {
    echo Message('<p>No Gang...No Bar Silly.</p>', 'Error', true);
}
$_GET['attack'] = array_key_exists('attack', $_GET) && in_array($_GET['attack'], ['window', 'door'], true) ? $_GET['attack'] : null;
if (isset($_GET['attack'])) {
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE, null, true);
    }
    $errors = [];
    if ($_GET['attack'] === 'window' && $user_class->energypercent < 30) {
        $errors[] = '<p>You need to have at least 30% of your energy to attempt the window.</p>';
    }
    if ($_GET['attack'] === 'door' && $user_class->energypercent < 25) {
        $errors[] = '<p>You need to have at least 25% of your energy to attempt the door.</p>';
    }
    if (count($errors)) {
        display_errors($errors, true);
    }
    $csrfg = csrf_create('csrfg', false);
    if ($_GET['attack'] === 'window') {
        $chance = mt_rand(1, 5);
        if ($chance === 2) {
            $db->query('UPDATE users SET hospital = 300, hwhen = ?, hhow = \'80\', energy = GREATEST(energy - FLOOR(energy * 0.1), 0) WHERE id = ?');
            $db->execute([date('g:i:sa'), $user_class->id]);
            echo Message('<p>You failed. A bullet clipped you while you took aim, it\'s off to the ward to get stitches for you.</p><p><a href="plugins/gangbar.php?csrfg='.htmlspecialchars($csrfg, ENT_QUOTES, 'UTF-8').'">[Back to The Bar]</a></p>', 'Gang Bar', true);
        } else {
            $db->query('INSERT INTO gangattacks (gang, no) VALUES (?, 3) ON DUPLICATE KEY UPDATE no = no + 3');
            $db->execute([$user_class->gang]);
            $db->query('UPDATE users SET energy = GREATEST(energy - FLOOR(energy * 0.1), 0), barpoints = barpoints + 3 WHERE id = ?');
            $db->execute([$user_class->id]);
            echo Message('<p>Your shot took out the window and killed the thug inside. You just earned <span style="color:orange;">3 Points</span> towards your gang\'s total score.</p><p><a href="plugins/gangbar.php?csrfg='.htmlspecialchars($csrfg, ENT_QUOTES, 'UTF-8').'">[Back to The Bar]</a></p>', 'Gang Bar', true);
        }
    } elseif ($_GET['attack'] === 'door') {
        $chance = mt_rand(1, 3);
        if ($chance === 2) {
            $db->query('UPDATE users SET hospital = 300, hwhen = ?, hhow = \'80\', energy = GREATEST(energy - FLOOR(energy * 0.2), 0) WHERE id = ?');
            $db->execute([date('g:i:sa'), $user_class->id]);
            echo Message('<p>You failed. A bullet clipped you while you took aim, it\'s off to the ward for stitches for you.</p><p><a href="plugins/gangbar.php?csrfg='.htmlspecialchars($csrfg, ENT_QUOTES, 'UTF-8').'">[Back to The Bar]</a></p>', 'Gang Bar', true);
        } else {
            $db->query('INSERT INTO gangattacks (gang, no) VALUES (?, 6) ON DUPLICATE KEY UPDATE no = no + 6');
            $db->execute([$user_class->gang]);
            $db->query('UPDATE users SET energy = GREATEST(energy - FLOOR(energy * 0.1), 0), barpoints = barpoints + 6 WHERE id = ?');
            $db->execute([$user_class->id]);
            echo Message('<p>You smashed the door to pieces and killed the guy guarding it. You just earned <span style="color:orange;">6 Points</span> towards your gang\'s total score.</p><p><a href="plugins/gangbar.php?csrfg='.htmlspecialchars($csrfg, ENT_QUOTES, 'UTF-8').'">[Back to The Bar]</a></p>', 'Gang Bar', true);
        }
    }
}
if (!isset($csrfg)) {
    $csrfg = csrf_create('csrfg', false);
}
?><tr>
    <th class="content-head">The Gang Bar</th>
</tr>
<tr>
    <td class="content">
        <center>
            <img src="images/barscene.webp" width="350" alt="Bar Scene"/><br/><br/>
            <p>Score as many points as possible over a 1 hour period.</p>
            Windows will award a lower score but are easier to succeed.</p>
            The door will award higher scores but you will die more often.</p>
            Gang with the high score over the hour wins $250,000 for its vaults.</p><br />
            <p>Just click your target below and good luck.</p><br />
            <table width="100%" class="pure-table">
                <thead>
                    <tr>
                        <th class="gangbar-target-th">Top Window</th>
                        <th class="gangbar-target-th">Boarded Window</th>
                        <th class="gangbar-target-th">Main Door</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="gangbar-target-td"><a href="plugins/gangbar.php?attack=window&amp;csrfg=<?php echo htmlspecialchars($csrfg, ENT_QUOTES, 'UTF-8'); ?>"><img src="images/topwindow.png" width="71" height="113" alt="Top Window" border="0"/></a></td>
                        <td class="gangbar-target-td"><a href="plugins/gangbar.php?attack=window&amp;csrfg=<?php echo htmlspecialchars($csrfg, ENT_QUOTES, 'UTF-8'); ?>"><img src="images/bottomwindow.png" width="71" height="113" alt="Boarded Window" border="0"/></a></td>
                        <td class="gangbar-target-td"><a href="plugins/gangbar.php?attack=door&amp;csrfg=<?php echo htmlspecialchars($csrfg, ENT_QUOTES, 'UTF-8'); ?>"><img src="images/bardoor.png" width="71" height="113" alt="Main Door" border="0"/></a></td>
                    </tr>
                </tbody>
            </table>
        </center>
    </td>
</tr>
<tr>
    <th class="content-head">Last 10 Winners</th>
</tr>
<tr>
    <td class="content">
        <table width="100%" class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <th>Winner</th>
                    <th>When</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $db->query('SELECT gang, kills, time FROM goth WHERE gang > 0 ORDER BY time DESC LIMIT 10');
                $db->execute();
                $gothRows = $db->fetch();
                if ($gothRows !== null) {
                    foreach ($gothRows as $gothRow) {
                        $l_winner = new Gang((int)$gothRow['gang']);
                        echo '<tr><td>' . $l_winner->formattedname . '</td><td>' . howlongago($gothRow['time']) . '</td><td>' . prettynum((int)$gothRow['kills']) . ' Points</td></tr>';
                    }
                } else {
                    echo '<tr><td colspan="3"><p>Sadly there are no winners yet.</p></td></tr>';
                }
                ?>
            </tbody>
        </table>
    </td>
</tr>
