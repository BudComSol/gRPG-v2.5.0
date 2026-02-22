<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
if (array_key_exists('deposit', $_POST)) {
    if (!csrf_check('dep_csrf', $_POST)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    $_POST['damount'] = array_key_exists('damount', $_POST) && ctype_digit(str_replace(',', '', $_POST['damount'])) ? (float)str_replace(',', '', $_POST['damount']) : $user_class->money;
    if ($_POST['damount'] > $user_class->money) {
        echo Message('You don\'t have that much money', 'Error', true);
    }
    $db->query('UPDATE users SET money = GREATEST(money - ?, 0), bank = bank + ? WHERE id = ?');
    $db->execute([$_POST['damount'], $_POST['damount'], $user_class->id]);
    $user_class->money -= $_POST['damount'];
    $user_class->bank += $_POST['damount'];
    echo Message('The Money Has Been Deposited.');
}
if (array_key_exists('withdraw', $_POST)) {
    if (!csrf_check('wit_csrf', $_POST)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    $_POST['wamount'] = array_key_exists('wamount', $_POST) && ctype_digit(str_replace(',', '', $_POST['wamount'])) ? (float)str_replace(',', '', $_POST['wamount']) : $user_class->bank;
    if ($_POST['wamount'] > $user_class->bank) {
        echo Message('You don\'t have that much money in the bank numpty.', 'Error', true);
    }
    $db->query('UPDATE users SET bank = GREATEST(bank - ?, 0), money = money + ? WHERE id = ?');
    $db->execute([$_POST['wamount'], $_POST['wamount'], $user_class->id]);
    $user_class->money += $_POST['wamount'];
    $user_class->bank -= $_POST['wamount'];
    echo Message('The Money Has Been Withdrawn.');
}
if (array_key_exists('open', $_GET)) {
    if (!csrf_check('csrfg', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    if (5000 > $user_class->money) {
        echo Message('<p>You don\'t have enough money to open an account, numpty.</p>', 'Error', true);
    }
    $db->query('UPDATE users SET whichbank = 1, money = GREATEST(money - 5000, 0) WHERE id = ?');
    $db->execute([$user_class->id]);
    $user_class = new User($user_class->id);
    echo Message('<p>Your new bank account has been opened and is ready for use.</p>');
}
$interest = $user_class->rmdays > 0 ? .04 : .02;
$interest = ceil($user_class->bank * $interest);
?><tr>
    <th class="content-head">Bank</th>
</tr><?php
if (!$user_class->whichbank) {
    echo Message('<p>You don\'t currently have an account with us.</p> Would you like to open one for '.prettynum(5000, true).'?<br /><a href="plugins/bank.php?open&amp;csrfg='.csrf_create('csrfg', false).'"><p>Yes Please</p></a>', 'Open An Account', true);
}
?><tr>
    <td class="content">
        <p>Welcome to the bank, you currently have <?php echo prettynum($user_class->bank, true); ?> in your account.</p>
        You will make <?php echo prettynum($interest, true); ?> from interest next rollover.<br /><br />
        <form action="plugins/bank.php" method="post" class="pure-form pure-form-aligned">
            <?php echo csrf_create('wit_csrf'); ?>
            <div class="pure-control-group">
                <label for="wamount">Withdraw</label>
                <input type="text" name="wamount" id="wamount" value="<?php echo format($user_class->bank); ?>" size="10" maxlength="20" />
            </div>
            <div class="pure-controls">
                <button type="submit" name="withdraw" class="pure-button pure-button-primary">Withdraw</button>
            </div>
           <br>
        </form>
        <form action="plugins/bank.php" method="post" class="pure-form pure-form-aligned">
            <?php echo csrf_create('dep_csrf'); ?>
            <div class="pure-control-group">
                <label for="damount">Deposit</label>
                <input type="text" name="damount" id="damount" value="<?php echo format($user_class->money); ?>" size="10" maxlength="20" />
            </div>
            <div class="pure-controls">
                <button type="submit" name="deposit" class="pure-button pure-button-secondary">Deposit</button>
            </div>
        </form>
    </td>
</tr>
