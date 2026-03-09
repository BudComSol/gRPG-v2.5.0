<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
checkUserStatus();
if (isset($_GET['id'])) {
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE, null, true);
    }
    $db->query('SELECT * FROM crimes WHERE id = ?', [$_GET['id']]);
    $row = $db->fetch(true);
    if ($row === null) {
        echo Message('<p>That crime doesn\'t exist, ya numpty.</p>', 'Error', true);
    }
    $nerve = $row['nerve'];
    $stext = '[[We currently don\'t have a success message for this crime :( You can help us by submitting your idea for a message in the crime section of the forums!]]';
    $ctext = '[[We currently don\'t have a "You got caught" message for this crime :( You can help us by submitting your idea for a message in the crime section of the forums!]]';
    $ftext = '[[We currently do not have a failure message for this crime :( You can help us by submitting your idea for a message in the crime section of the forums!]]';
    $stexta = (array)explode('^', $row['stext']);
    $stext = !empty($stexta[0]) ? $stexta[array_rand($stexta)] : $stext;
    $ctexta = (array)explode('^', $row['ctext']);
    $ctext = !empty($ctexta[0]) ? $ctexta[array_rand($ctexta)] : $ctext;
    $ftexta = (array)explode('^', $row['ftext']);
    $ftext = !empty($ftexta[0]) ? $ftexta[array_rand($ftexta)] : $ftext;
    $chance = max(mt_rand(1, (int)(100 * $nerve - ($user_class->speed / 35))), 1);
    $money = round(25 * $nerve) + 15 * ($nerve - 1);
    if ($user_class->class === 'Thief') {
        $money = round(25 * $nerve) + 15 * ($nerve - 1) + 5;
    }
    $exp = $money;
    if ($nerve > $user_class->nerve) {
        echo Message('<p>You don\'t have enough nerve for that crime.</p>', 'Error', true);
    } else {
        $csrfg = csrf_create('csrfg', false);
        if ($chance <= 75) {
            $db->query('UPDATE users SET experience = experience + ?, crimesucceeded = crimesucceeded + 1, crimemoney = crimemoney + ?, money = money + ?, nerve = GREATEST(nerve - ?, 0) WHERE id = ?');
            $db->execute([$exp, $money, $money, $nerve, $user_class->id]);
            $user_class->nerve = max($user_class->nerve - $nerve, 0);
            $user_class->nervepercent = (float)($user_class->maxnerve > 0 ? min(100, floor(($user_class->nerve / $user_class->maxnerve) * 100)) : 0);
            $user_class->formattednerve = $user_class->nerve.' / '.$user_class->maxnerve.' ['.$user_class->nervepercent.'%]';
            echo Message($stext.'<p><span style="color:green;">Success pal, you receive '.$exp.' exp and '.prettynum($money, true).'.</span></p><br /><a href="plugins/crime.php?id='.$_GET['id'].'&amp;csrfg='.$csrfg.'">Retry</a> | <a href="plugins/crime.php">Back</a>', 'Error', true);
        } elseif ($chance >= 150) {
            $db->query('UPDATE users SET crimefailed = crimefailed + 1, jail = ?, nerve = GREATEST(nerve - ?, 0) WHERE id = ?');
            $db->execute([$_GET['id'] * 600, $nerve, $user_class->id]);
            $user_class->nerve = max($user_class->nerve - $nerve, 0);
            $user_class->nervepercent = (float)($user_class->maxnerve > 0 ? min(100, floor(($user_class->nerve / $user_class->maxnerve) * 100)) : 0);
            $user_class->formattednerve = $user_class->nerve.' / '.$user_class->maxnerve.' ['.$user_class->nervepercent.'%]';
            echo Message($ctext.'<br /><br /><span style="color:red;">You were caught.</span> You were hauled off to jail for '.($_GET['id'] * 10).' minutes.', 'Error', true);
        } else {
            $db->query('UPDATE users SET crimefailed = crimefailed + 1, nerve = GREATEST(nerve - ?, 0) WHERE id = ?');
            $db->execute([$nerve, $user_class->id]);
            $user_class->nerve = max($user_class->nerve - $nerve, 0);
            $user_class->nervepercent = (float)($user_class->maxnerve > 0 ? min(100, floor(($user_class->nerve / $user_class->maxnerve) * 100)) : 0);
            $user_class->formattednerve = $user_class->nerve.' / '.$user_class->maxnerve.' ['.$user_class->nervepercent.'%]';
            echo Message($ftext.'<span style="color:red;"><p>Sorry But You Failed To Commit This Crime.</p></span><br /><a href="plugins/crime.php?id='.$_GET['id'].'&amp;csrfg='.$csrfg.'">Retry</a> | <a href="plugins/crime.php">Back</a>', 'Error', true);
        }
    }
}
if (!isset($csrfg)) {
    $csrfg = csrf_create('csrfg', false);
}
$db->query('SELECT id, name, nerve FROM crimes ORDER BY nerve ');
$db->execute();
$rows = $db->fetch();
?><tr>
    <th class="content-head">Commit Crimes</th>
</tr>
<tr>
    <td class="content">
        <p>Choose a crime and see if you can get away with it, you won't always.</p>
    </td>
</tr>
<tr>
<tr>
    <td class="content">
        <table width="100%" class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <th width="50%">Name</th>
                    <th width="25%">Nerve</th>
                    <th width="25%">Action</th>
                </tr>
            </thead><br><?php
if ($rows !== null) {
        foreach ($rows as $row) {
            ?>
        <tr>
            <td><?php echo format($row['name']); ?></td>
            <td><?php echo format($row['nerve']); ?></td>
            <td>[<a href="plugins/crime.php?id=<?php echo $row['id']; ?>&amp;csrfg=<?php echo $csrfg; ?>">Commit</a>]</td>
        </tr><?php
        }
    } else {
        ?>
        <tr>
            <td colspan="3" class="center"><p>There are no crimes to commit at this time.</p></td>
        </tr><?php
    }
?></table>
   <br>
    </td>
</tr>
