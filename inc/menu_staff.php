<?php
declare(strict_types=1);
if (!defined('GRPG_INC')) {
    exit;
}
$db->query('SELECT COUNT(id) FROM tickets WHERE status IN (\'open\', \'pending\')');
$db->execute();
$tickets = $db->result();
?>
<div class="menu-user-card">
    <a href="plugins/profiles.php?id=<?php echo $user_class->id; ?>">
        <img src="<?php echo htmlspecialchars((!empty($user_class->avatar) && preg_match('/^(https?:\/\/|[a-zA-Z0-9_\-\/\.]+$)/', $user_class->avatar)) ? $user_class->avatar : 'images/noimage.png', ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($user_class->username, ENT_QUOTES, 'UTF-8'); ?>'s avatar" class="menu-avatar"/>
    </a>
    <div class="menu-username"><a href="plugins/profiles.php?id=<?php echo $user_class->id; ?>"><?php echo htmlspecialchars($user_class->username, ENT_QUOTES, 'UTF-8'); ?></a></div>
</div>
<div>
    <div class="headbox leftmenu">Game</div>
    <a href="index.php" class="leftmenu">Back To Game</a>
</div>
<div>
    <div class="headbox leftmenu">Control Panel</div>
    <a href="plugins/control.php?page=marquee" class="leftmenu">Marquee</a>
    <a href="plugins/massmail.php" class="leftmenu">Mass Mail</a>
    <a href="plugins/control.php?page=site_settings" class="leftmenu">Site Closed</a>    
    <a href="plugins/control.php?page=rmoptions" class="leftmenu">RM Options</a>
    <a href="plugins/control.php?page=rmpacks" class="leftmenu">RM Upgrades</a>
    <a href="plugins/control.php?page=setplayerstatus" class="leftmenu">Player Options</a>    
    <a href="plugins/control.php?page=referrals" class="leftmenu">Manage Referrals</a>
    <div class="headbox">Modifications</div>
    <a class="leftmenu" href="plugins/control.php?page=jobs">Manage Jobs</a>
    <a class="leftmenu" href="plugins/control.php?page=cars">Manage Cars</a>
    <a class="leftmenu" href="plugins/control.php?page=playeritems">Manage Items</a>
    <a class="leftmenu" href="plugins/control.php?page=cities">Manage Cities</a>
    <a class="leftmenu" href="plugins/control.php?page=voting">Manage Voting</a>
    <a class="leftmenu" href="plugins/control.php?page=crimes">Manage Crimes</a>
    <a class="leftmenu" href="plugins/control.php?page=forum">Manage Forum</a>
    <a class="leftmenu" href="plugins/control.php?page=houses">Manage Houses</a>    
    <a class="leftmenu" href="plugins/control.php?page=giveuseritem">Give Item To User</a>    
</div>
<div>
    <div class="headbox leftmenu">Stats</div>
    <div class="menu-stats">
        Money: <?php echo prettynum($user_class->money, true); ?><br>
        Points: <?php echo format($user_class->points); ?><br>
        <div class="menu-stat-label">HP</div>
        <div class="bar_a"><div class="bar_b bar_b_pink" style="width:<?php echo (int)$user_class->hppercent; ?>%;"></div></div>
        <div class="menu-stat-label">Energy</div>
        <div class="bar_a"><div class="bar_b bar_b_pink" style="width:<?php echo (int)$user_class->energypercent; ?>%;"></div></div>
        <div class="menu-stat-label">Nerve</div>
        <div class="bar_a"><div class="bar_b" style="width:<?php echo (int)$user_class->nervepercent; ?>%;"></div></div>
        <div class="menu-stat-label">Awake</div>
        <div class="bar_a"><div class="bar_b" style="width:<?php echo (int)$user_class->awakepercent; ?>%;"></div></div>
    </div>
</div>
<div>
    <div class="headbox leftmenu">Miscellaneous</div>
    <a href="plugins/managetickets.php" class="leftmenu">Support Desk [<?php echo $tickets; ?>]</a>
</div>
