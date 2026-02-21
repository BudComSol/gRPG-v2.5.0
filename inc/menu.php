<?php
declare(strict_types=1);
if (!defined('GRPG_INC')) {
    exit;
}
global $owner;
?>
<div class="mainbox">
    <div class="headbox">Menu</div>
    
    <a class="leftmenu style1" href="index.php">Home</a>
    <a class="leftmenu" href="plugins/classifieds.php">Ads</a>
    <a class="leftmenu" href="plugins/city.php">City</a>
    <a class="leftmenu" href="plugins/jail.php">Jail <!_-jail-_!></a>
    <a class="leftmenu" href="plugins/bank.php">Bank</a>
    <a class="leftmenu" href="plugins/crime.php">Crime</a>
    <a class="leftmenu" href="plugins/forum.php">Forum</a>
    <a class="leftmenu" href="plugins/todo.php">To-Do</a>
    <a class="leftmenu" href="plugins/events.php">Events <!_-events-_!></a>    
    <a class="leftmenu" href="plugins/city.php"><!_-cityname-_!></a>
    <a class="leftmenu" href="plugins/pms.php">Mailbox <!_-mail-_!></a>    
    <a class="leftmenu" href="plugins/hospital.php">Hospital <!_-hospital-_!></a>
    <a class="leftmenu" href="plugins/inventory.php">Inventory</a>
    <a class="leftmenu" href="<?php echo !$user_class->gang ? 'plugins/create' : 'plugins/'; ?>gang.php">Your Gang</a>
    <a class="leftmenu" href="plugins/gym.php">Gymnasium</a>    
    <a class="leftmenu" href="plugins/rmstore.php">Game Store</a><?php
    if ($user_class->admin == 1) {
        ?>
        <div class="headbox" style="color:yellow;">Staff</div>
        <a class="leftmenu" href="plugins/control.php">Control Panel</a><?php
    } ?>
    <div class="headbox">Account</div><?php
if ($user_class->rmdays) {
        ?><a class="leftmenu" href="plugins/blocklist.php">Blocklist</a><?php
    }
?><a class="leftmenu" href="index.php?logout">Logout</a>
    <a class="leftmenu" href="plugins/preferences.php">Preferences</a>
    <a class="leftmenu" href="plugins/tickets.php">Support Desk</a>    
    <a class="leftmenu" href="plugins/cpassword.php">Change Password</a>    
    <!--<a class="leftmenu" href="changestyle.php">Color Scheme</a>-->
</div>
