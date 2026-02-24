<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
$drug = ['no-doze' => ['cost' => 10000, 'col' => 'nodoze'], 'steroids' => ['cost' => 2500, 'col' => 'genericsteroids']];
$errors = [];
$_GET['buy'] = array_key_exists('buy', $_GET) && in_array(strtolower($_GET['buy']), ['no-doze', 'steroids']) ? strtolower(trim($_GET['buy'])) : null;
if (!empty($_GET['buy'])) {
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if ($drug[$_GET['buy']]['cost'] > $user_class->money) {
        $errors[] = '<p>Sorry but you don\'t have enough money to buy drugs.</p>';
    }
    if (!count($errors)) {
        $db->query('UPDATE users SET money = GREATEST(money - ?, 0), '.$drug[$_GET['buy']]['col'].' = '.$drug[$_GET['buy']]['col'].' + 1 WHERE id = ?');
        $db->execute([$drug[$_GET['buy']]['cost'], $user_class->id]);
        echo Message('You\'ve purchased a '.($_GET['buy'] === 'no-doze' ? 'No-Doze' : 'Steroid'));
    }
}
?><tr>
    <th class="content-head">Pharmacy</th>
</tr><?php
if (count($errors)) {
    display_errors($errors);
}
$csrfg = csrf_create('csrfg', false);
?><tr>
    <td class="content">
        <p>We offer a range of medical supplies here for all your medical needs.</p>
        <p>We assume these drugs won't be abused, we have a strict no drug-abuse policy in Generica.</p>
    </td>
</tr>
<tr>
    <td class="content">
        <table width="100%" class="center">
            <tr>
                <td width="25%">
                    <img src="../images/pharmacy/nodoze.png" width="100" height="100" style="border: 1px solid #333;">
                     <p>NoDoze Pills</p>
                    <?php echo prettynum($drug['no-doze']['cost'], true); ?><br />
                     <a href="plugins/pharmacy.php?buy=No-Doze&amp;csrfg=<?php echo $csrfg; ?>"><p>Buy Drugs</p></a>
                </td>
                <td width="25%">
                    <img src="../images/pharmacy/steroids.png" width="100" height="100" style="border: 1px solid #333;">
                     <p>Generic Steroids</p>
                    <?php echo prettynum($drug['steroids']['cost'], true); ?><br />
                     <a href="plugins/pharmacy.php?buy=Steroids&amp;csrfg=<?php echo $csrfg; ?>"><p>Buy Drugs</p></a>
                </td>
            </tr>
        </table>
    </td>
</tr>
