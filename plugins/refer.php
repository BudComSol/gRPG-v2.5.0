<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
$db->query('SELECT id, referred FROM referrals WHERE referrer = ? ORDER BY referrer ');
$db->execute([$user_class->id]);
$rows = $db->fetch();
?><tr>
    <th class="content-head">Refer To Earn Points</th>
</tr>
<tr>
    <td class="content">
        <p><b>Your Referer Link: <?php echo BASE_URL; ?>register.php?referer=<?php echo $user_class->id; ?></b></p>
        <p>UPDATE: You will receive your points only <em>after</em> we filter out multis.</p>
        <p>This is due to too many people abusing the referral system.</p>
        <p>Because we have to do this manually now, it could take anywhere from an hour to 2 days, but rest assured that you will receive your points.</p>
    </td>
</tr>
<tr>
    <th class="content-head">Players You Have Referred</th>
</tr>
<tr>
    <td class="content"><?php
if ($rows !== null) {
        foreach ($rows as $row) {
            $referred = new User(Get_ID($row['referred'])); ?><div><?php echo $referred->formattedname; ?> - <?php echo !$row['credited'] ? 'Pending' : 'Accepted'; ?></div><?php
        }
    } else {
        ?><p>You haven't referred anyone yet, c'mon and get the lead out.</p><?php
    }
?></td>
</tr>
