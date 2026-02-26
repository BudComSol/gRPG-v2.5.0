<?php
declare(strict_types=1);
global $owner;
require_once __DIR__.'/../inc/header.php';
$errors = [];
if (array_key_exists('cancel', $_GET)) {
    echo Message('You\'ve cancelled your purchase');
}
if (array_key_exists('success', $_GET)) {
    echo Message('Your purchase was successful');
}
if (array_key_exists('reset', $_GET)) {
    unset($_SESSION['customer']);
}
if (array_key_exists('update_customer', $_POST)) {
    $_POST['customer'] = array_key_exists('customer', $_POST) && is_string($_POST['customer']) ? strip_tags(trim($_POST['customer'])) : null;
    if (empty($_POST['customer'])) {
        $errors[] = 'You didn\'t enter a valid recipient name';
    }
    $id = Get_ID($_POST['customer']);
    if (!userExists($id)) {
        $errors[] = 'The recipient you selected doesn\'t exist';
    }
    if (count($errors)) {
        display_errors($errors);
    } else {
        $_SESSION['customer'] = $id;
    }
}
if (!array_key_exists('customer', $_SESSION)) {
    $_SESSION['customer'] = $user_class->id;
}
$target = new User($_SESSION['customer']);
$db->query('SELECT * FROM rmstore_packs WHERE enabled = 1 ORDER BY cost ');
$db->execute();
$packs = $db->fetch();
?><tr>
    <th class="content-head">Respected Citizens</th>
</tr>
<tr>
    <td class="content">
        <p>Are what you become by purchasing RM Days.</p>
        <p>* For those days you will gain energy and nerve twice as quick.</p>
        <p>* For those days you will gain 4% bank interest instead of 2%.</p>
    </td>
</tr>
<tr>
    <th class="content-head">Stuff To Buy</th>
</tr>
<tr>
    <td class="content">
        <form action="/plugins/rmstore.php" method="post" class="pure-form-rmstore">
            <div class="pure-info-message"><p>You're currently purchasing an RMStore Upgrade for <?php echo $target->id == $user_class->id ? 'yourself' : $target->formattedname; ?></p><?php if ($target->id != $user_class->id) : ?> <span class="small italic">- [<a href="plugins/rmstore.php?reset">Reset</a>]</span><?php endif; ?></div>
            <?php echo csrf_create('custom_for'); ?>
            <div class="pure-control-group">
                <label for="customer">Purchase Upgrade For:</label>
                <input type="text" name="customer" id="customer" placeholder="<?php echo format($target->username); ?>" />
            </div><br>
            <div class="pure-controls">
                <button type="submit" name="update_customer" class="pure-button pure-button-primary">Update Recipient</button>
            </div>
        </form>
    </td>
</tr><?php
if (RMSTORE_BOGOF == true && RMSTORE_DISCOUNT > 0) {
    echo Message('There\'s '.RMSTORE_DISCOUNT.'% off all RMStore Upgrades <em>and</em> they\'re on a &ldquo;Buy One Get One Free&rdquo; offer!');
} elseif (RMSTORE_DISCOUNT > 0) {
    echo Message('There\'s '.RMSTORE_DISCOUNT.'% off all RMStore Upgrades!');
} elseif (RMSTORE_BOGOF == true) {
    echo Message('All RMStore Upgrades are Buy One Get One Free!');
}
?><tr>
    <td class="content">
        <table width="100%" cellspacing="1">
            <tr style="background:#910503;text-align:center;">
                <td>Package</td>
                <td>RM Days</td>
                <td>Points</td>
                <td>Prostitutes</td>
                <td>Items</td>
                <td>Cost</td>
                <td>Purchase</td>
            </tr><?php
if ($packs !== null) {
        foreach ($packs as $pack) {
            $cost = $pack['cost'];
            if (RMSTORE_DISCOUNT > 0) {
                $cost -= ($pack['cost'] / 100) * RMSTORE_DISCOUNT;
            } ?><tr style="background:#181818;text-align:center;">
                        <td><?php echo format($pack['name']); ?></td>
                        <td><?php echo $pack['days'] ? format($pack['days']) : '-'; ?></td>
                        <td><?php echo $pack['points'] ? format($pack['points']) : '-'; ?></td>
                        <td><?php echo $pack['prostitutes'] ? format($pack['prostitutes']) : '-'; ?></td>
                        <td><?php
        if ($pack['items']) {
            $itemsArray = explode(',', $pack['items']);
            foreach ($itemsArray as $what) {
                [$qty, $item] = explode(':', $what);
                if (itemExists($item)) {
                    echo format($qty).'x '.item_popup($item).'<br />';
                }
            }
        } else {
            echo '-';
        } ?></td>
                        <td><?php echo $pack['cost'] == $cost ? formatCurrency($pack['cost']) : '<span class="strike">'.formatCurrency($pack['cost']).'</span><br /><span class="green">'.formatCurrency($cost).'</span>'; ?></td>
                        <td>
                            <?php if (PAYPAL_CLIENT_ID) : ?>
                            <div id="paypal-button-<?php echo $pack['id']; ?>"></div>
                            <?php else : ?>
                            <span style="color:orange;">PayPal not configured.<br>Set PAYPAL_CLIENT_ID in .env</span>
                            <?php endif; ?>
                        </td>
                    </tr><?php
        }
    } else {
        ?><tr>
                        <td colspan="7" class="centre" style="background:#181818;text-align:center;"><p>There are no RMStore Upgrades Available</p></td>
                    </tr><?php
    }
?></table>
    </td>
</tr>
<tr>
    <th class="content-head">Read This Or Die</th>
</tr>
<tr>
    <td class="content">
        <p>If you have any questions, PM me (<?php echo $owner->formattedname; ?>).<br />
        Before you buy you must be clear of the following things:<br /><br />
        1. NO Refunds.<br />
        2. And you can still be banned for breaking the rules whether you have donated or not.</p>
    </td>
</tr><?php
if (PAYPAL_CLIENT_ID && $packs !== null) : ?>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_CLIENT_ID; ?>&currency=<?php echo RMSTORE_CURRENCY; ?>"></script>
<script>
<?php foreach ($packs as $pp) :
    $ppCost = $pp['cost'];
    if (RMSTORE_DISCOUNT > 0) {
        $ppCost -= ($pp['cost'] / 100) * RMSTORE_DISCOUNT;
    }
    $ppCost = number_format((float)$ppCost, 2, '.', '');
?>
paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                reference_id: <?php echo json_encode((string)$pp['id']); ?>,
                description: <?php echo json_encode($pp['name']); ?>,
                custom_id: <?php echo json_encode($pp['id'] . ':' . $user_class->id . ':' . $_SESSION['customer']); ?>,
                amount: {
                    currency_code: <?php echo json_encode(RMSTORE_CURRENCY); ?>,
                    value: <?php echo json_encode($ppCost); ?>
                }
            }]
            // Note: IPN notify_url must be configured in your PayPal Developer Dashboard under
            // App Settings > Webhooks/IPN, or in your PayPal account under Profile > Instant Payment Notifications
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function() {
            window.location.href = <?php echo json_encode(BASE_URL . 'plugins/rmstore.php?success'); ?>;
        });
    },
    onCancel: function(data) {
        window.location.href = <?php echo json_encode(BASE_URL . 'plugins/rmstore.php?cancel'); ?>;
    }
}).render('#paypal-button-<?php echo $pp['id']; ?>');
<?php endforeach; ?>
</script>
<?php endif; ?>
