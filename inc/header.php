<?php
declare(strict_types=1);
if (!defined('GRPG_INC')) {
    define('GRPG_INC', true);
}
if (!function_exists('microtime_float')) {
    function microtime_float()
    {
        return microtime(true);
    }
}
define('LOAD_TIME_START', microtime_float());
require_once __DIR__.'/dbcon.php';
// Check if user is logged in
if (!array_key_exists('id', $_SESSION) || !$_SESSION['id'] || !is_numeric($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}
// Check for logout
if (array_key_exists('logout', $_GET)) {
    session_destroy();
    header('Location: home.php');
    exit;
}
// Update lastactive timestamp (throttled to once per minute to reduce database writes)
$db->query('UPDATE users SET lastactive = CURRENT_TIMESTAMP WHERE id = ? AND lastactive < DATE_SUB(NOW(), INTERVAL 1 MINUTE)', [(int)$_SESSION['id']]);
// Initialize logged-in user
$user_class = new User($_SESSION['id']);
if (!$user_class->id) {
    session_destroy();
    header('Location: login.php');
    exit;
}
$time = date('F d, Y g:i:sa');
$site_url = getenv('SITE_URL');
ob_start(); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head><?php
if ($site_url !== false && $site_url !== '') {
    ?>
    <base href="<?php echo rtrim($site_url, '/').'/'; ?>"/>
    <?php
    }
    ?>
    <title>gRPG - A Full Stack Game Engine</title>
    <meta name="description" content="gRPG is a full stack game engine with which to build your own RPG, MMORPG or PBBG game.">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" type="text/css" media="all" href="css/login.css"/>
    <link rel="stylesheet" type="text/css" media="all" href="css/style.css"/>    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/g/pure@0.6.2(buttons-min.css+grids-min.css+forms-min.css)"
    integrity="sha384-+YK1ur0Mr74WEZWTMC6oMb5fojhkGm6EpjgVheKlE9urf2PbykYP7MxdwPpruQB8" crossorigin="anonymous"/>
    <link rel="stylesheet" href="css/custom-styles.css">      
    <script src="js/cookie-consent.js"></script>
    <?php
    $ga_id = settings('google_analytics');
    if (!empty($ga_id)) { ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars($ga_id, ENT_QUOTES, 'UTF-8'); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo htmlspecialchars($ga_id, ENT_QUOTES, 'UTF-8'); ?>');
    </script>
    <?php } ?>
</head>
<body>
<!-- Cookie Consent Banner -->
<div id="cookie-consent-banner">
    <div class="cookie-content">
        <div class="cookie-message">
            We use cookies to ensure you get the best experience on our website. 
            These include essential cookies for site functionality and session management. 
            By continuing to use this site, you consent to our use of cookies. 
            <a href="<?php echo ($site_url !== false && $site_url !== '') ? '' : '/'; ?>inc/privacy.php">Learn more</a>
        </div>
        <div class="cookie-buttons">
            <button id="cookie-consent-accept">Accept</button>
        </div>
    </div>
</div>
<table bgcolor="#1E1E1E" border="0" cellspacing="0" cellpadding="0" width="100%">
    
    <tr>
        <td colspan="3" class="pos1" height="55" valign="middle">
            <div class="topbox">
                <table width="800">
                    <tr>
                        <td width="50%" class="center-img"><img src="images/logos/logo.png" alt="gRPG Main Logo"/></td>

                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <?php
    $db->query('SELECT title, message FROM ads ORDER BY time_added DESC LIMIT 10');
    $db->execute();
    $ads_rows = $db->fetch();
    if ($ads_rows !== null && count($ads_rows) > 0) {
        $ad_items = [];
        foreach ($ads_rows as $ad_row) {
            $ad_items[] = format($ad_row['title']).': '.format($ad_row['message']);
        }
        ?>
    <tr>
        <td colspan="3" class="content-head content-head-marquee">
            <marquee class="content-marquee" behavior="scroll" direction="left"><?php echo implode(' &nbsp;&bull;&nbsp; ', $ad_items); ?></marquee>
        </td>
    </tr>
    <?php } ?>
    <tr>
        <td>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top" width="150"><?php
                        if (defined('STAFF_FILE') && $user_class->admin) {
                            require_once __DIR__.'/menu_staff.php';
                        } else {
                            require_once __DIR__.'/menu.php';
                        }
                    ?></td>
                    <td valign="top">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <td width="10"></td>
                                <td valign="top" class="mainbox">
                                    <table class="content"><?php
// The old installer header used __destruct() to auto-close HTML.
// Logged-in pages (index.php, plugins/*) rely on this automatic closure.
// Using register_shutdown_function maintains compatibility without modifying all pages.
register_shutdown_function(function() {
    require_once __DIR__.'/footer.php';
});
