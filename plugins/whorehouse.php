<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';

$spend = $_GET['spend'] ?? '';
$cash = $_GET['cash'] ?? '';

if ($spend !== '') {
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    
    $spendAmounts = [
        '1' => 1000,
        '5' => 5000,
        '10' => 10000,
        '20' => 20000,
        '50' => 50000
    ];

    $errors = [];
    
    if (!array_key_exists($spend, $spendAmounts)) {
        $errors[] = 'Invalid amount selected';
    }
    
    if (!count($errors)) {
        $cost = $spendAmounts[$spend];
        $hookersToAdd = (int)$spend;
        
        if ($user_class->hookers + $hookersToAdd > 400) {
            echo Message("You can't have more than 400 hookers! The pimp union won't allow it!");
        } elseif ($user_class->money < $cost) {
            $errors[] = 'You don\'t have enough money for that';
        } else {
            $db->query('UPDATE users SET money = GREATEST(money - ?, 0), hookers = hookers + ? WHERE id = ?');
            $db->execute([$cost, $hookersToAdd, $user_class->id]);
            echo Message('You\'ve purchased '.$hookersToAdd.' hooker'.($hookersToAdd > 1 ? 's' : '').' for '.prettynum($cost, true));
        }
    }
    
    if (count($errors)) {
        display_errors($errors);
    }
}

if ($cash !== '') {
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    
    $errors = [];
    
    if ($user_class->hookers <= 0) {
        $errors[] = 'You don\'t have any hookers to cash out';
    }
    
    if (!count($errors)) {
        $earnings = $user_class->hookers * 500;
        $db->query('UPDATE users SET money = money + ?, hookers = 0 WHERE id = ?');
        $db->execute([$earnings, $user_class->id]);
        echo Message('Your hookers earned you '.prettynum($earnings, true).'! They\'ve been released.');
    }
    
    if (count($errors)) {
        display_errors($errors);
    }
}

$csrfg = csrf_create('csrfg', false);
?><tr>
    <th class="content-head">The Whorehouse</th>
</tr>
<tr>
    <td class="content">
        Welcome to the Whorehouse! Here you can hire hookers to earn money for you. Each hooker earns you $500 when you cash out, but you can't have more than 400 hookers at a time.
    </td>
</tr>
<tr>
    <td class="content">
        <strong>Current Hookers:</strong> <?php echo prettynum($user_class->hookers); ?><br />
        <strong>Your Money:</strong> <?php echo prettynum($user_class->money, true); ?>
    </td>
</tr>
<tr>
    <th class="content-head">Hire Hookers</th>
</tr>
<tr>
    <td class="content">
        <table width="100%" class="pure-table pure-table-horizontal">
            <tr>
                <td width="50%">1 Hooker - <?php echo prettynum(1000, true); ?></td>
                <td width="50%">[<a href="whorehouse.php?spend=1&amp;csrfg=<?php echo $csrfg; ?>">Hire</a>]</td>
            </tr>
            <tr>
                <td>5 Hookers - <?php echo prettynum(5000, true); ?></td>
                <td>[<a href="whorehouse.php?spend=5&amp;csrfg=<?php echo $csrfg; ?>">Hire</a>]</td>
            </tr>
            <tr>
                <td>10 Hookers - <?php echo prettynum(10000, true); ?></td>
                <td>[<a href="whorehouse.php?spend=10&amp;csrfg=<?php echo $csrfg; ?>">Hire</a>]</td>
            </tr>
            <tr>
                <td>20 Hookers - <?php echo prettynum(20000, true); ?></td>
                <td>[<a href="whorehouse.php?spend=20&amp;csrfg=<?php echo $csrfg; ?>">Hire</a>]</td>
            </tr>
            <tr>
                <td>50 Hookers - <?php echo prettynum(50000, true); ?></td>
                <td>[<a href="whorehouse.php?spend=50&amp;csrfg=<?php echo $csrfg; ?>">Hire</a>]</td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <th class="content-head">Cash Out</th>
</tr>
<tr>
    <td class="content">
        Release all your hookers and collect their earnings (<?php echo prettynum($user_class->hookers * 500, true); ?>).<br /><br />
        [<a href="whorehouse.php?cash=1&amp;csrfg=<?php echo $csrfg; ?>">Cash Out All Hookers</a>]
    </td>
</tr>
