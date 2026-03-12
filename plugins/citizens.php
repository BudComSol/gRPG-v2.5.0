<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
$db->query('SELECT id, lastactive FROM users ORDER BY id ');
$db->execute();
$rows = $db->fetch();
$csrfg = csrf_create('csrfg', false);
?><tr>
    <th class="content-head">Total Citizens</th>
</tr>
<tr>
    <td class="content">
        <table width="100%" class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <th width="5%">ID</th>
                    <th width="70%">Citizen</th>
                    <th width="25%">Actions</th>
                </tr>
            </thead><?php
foreach ($rows as $row) {
    $online = new User($row['id']); ?><tr>
                <td><?php echo $online->id; ?></td>
                <td><?php echo $online->formattedname; ?></td>
                <td><?php if ($online->id !== $user_class->id) { ?>
                    <a href="plugins/cagefights.php?fight=<?php echo $online->id; ?>&amp;csrfg=<?php echo $csrfg; ?>" class="pure-button pure-button-primary">&#9876; Cage Fight</a>
                <?php } ?></td>
            </tr><?php
}
?></table>
    </td>
</tr>
