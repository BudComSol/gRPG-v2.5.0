<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    echo Message('Invalid gang', 'Error', true);
}
$gang_class = new Gang((int)$_GET['id']);
if (!isset($gang_class->id)) {
    echo Message('The gang you selected doesn\'t exist', 'Error', true);
}
$db->query('SELECT id FROM users WHERE gang = ? ORDER BY experience DESC');
$db->execute([$gang_class->id]);
$rows = $db->fetch();
$db->query('SELECT playerid FROM ganginvites WHERE gangid = ?');
$db->execute([$gang_class->id]);
$invites = $db->fetch();
?><tr>
    <th class="content-head"><?php echo $gang_class->formattedname; ?></th>
</tr>
<tr>
    <td class="content">
        <table class="pure-table pure-table-horizontal" width="100%">
            <thead>
                <tr>
                    <td>Rank</td>
                    <td>Citizen</td>
                    <td>Level</td>
                    <td>Money</td>
                    <td>Online</td>
                </tr>
            </thead><?php
if ($rows !== null) {
        $rank = 1;
        foreach ($rows as $row) {
            $gang_member = new User($row['id']); ?><tr>
                    <td><?php echo $rank; ?></td>
                    <td><?php echo $gang_member->formattedname; ?></td>
                    <td><?php echo format($gang_member->level); ?></td>
                    <td><?php echo prettynum($gang_member->money, true); ?></td>
                    <td><?php echo $gang_member->formattedonline; ?></td>
                </tr><?php
        }
    } else {
        $ticket = generate_ticket('Broken Gang', 'ID: '.$_GET['id']); ?><tr>
                    <td colspan="5" class="center"><?php echo $gang_class->formattedname; ?> doesn't have any members<br />Wait.. This shouldn't happen..<br /><?php echo $ticket ? 'A ticket has been generated for you' : ''; ?></td>
                </tr><?php
    }
?></table>
    </td>
</tr>
<tr>
    <th class="content-head">Invited Citizens</th>
</tr>
<tr>
    <td class="content"><?php
if ($invites !== null) {
        foreach ($invites as $row) {
            $user = new User($row['playerid']); ?><div><?php echo $user->formattedname; ?></div><?php
        }
    } else {
        ?><p>No-one has been invited yet.</p><?php
    }
?></td>
</tr>
