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
$_login_url = (BASE_URL ? rtrim(BASE_URL, '/') . '/login.php' : '/login.php');
// Check if user is logged in
if (!array_key_exists('id', $_SESSION) || !$_SESSION['id'] || !is_numeric($_SESSION['id'])) {
    header('Location: ' . $_login_url);
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
    header('Location: ' . $_login_url);
    exit;
}
require_once __DIR__.'/updates.php';
$time = date('F d, Y g:i:sa');
$site_url = getenv('SITE_URL');
if ($site_url === false || $site_url === '') {
    // Auto-detect base URL when SITE_URL is not configured so that all
    // plugins/-prefixed links resolve correctly from within the plugins/ directory.
    $proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host_raw  = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
    // Strip anything that is not a valid hostname/port character to prevent Host header injection.
    $host = preg_replace('/[^\w\-\.:]/', '', $host_raw);
    $script_dir = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
    // If the current script lives inside plugins/, step up one level to the game root.
    if (basename($script_dir) === 'plugins') {
        $script_dir = dirname($script_dir);
    }
    $base_path = rtrim($script_dir, '/');
    $site_url  = $proto . '://' . $host . $base_path . '/';
}
ob_start(); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head><?php
    ?>
    <base href="<?php echo htmlspecialchars(rtrim($site_url, '/').'/'); ?>"/>
    <?php
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bangers&display=swap" rel="stylesheet">
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
            <a href="<?php echo ($site_url !== false && $site_url !== '') ? '' : '/'; ?>privacy.php">Learn more</a>
        </div>
        <div class="cookie-buttons">
            <button id="cookie-consent-accept">Accept</button>
        </div>
    </div>
</div>
<table bgcolor="#1E1E1E" border="0" cellspacing="0" cellpadding="0" width="100%">
    
    <tr>
        <td colspan="3" class="pos1" valign="middle">
            <div class="topbox">
                <?php
                $show_rotating_ads = false;
                if (isset($user_class) && (int)$user_class->rmdays <= 0
                    && settings('banner_ads_enabled') === 'on') {
                    $db->query('SELECT ad_code, display_seconds FROM banner_ads ORDER BY sort_order ASC, id ASC');
                    $db->execute();
                    $_banner_ads = $db->fetch();
                    if (!empty($_banner_ads)) {
                        $show_rotating_ads = true;
                    }
                }
                if ($show_rotating_ads) { ?>
                <div id="rotating-banner-ads">
                    <?php foreach ($_banner_ads as $_ba_idx => $_ba) { ?>
                    <div class="banner-ad-slide"
                         data-duration="<?php echo (int)$_ba['display_seconds']; ?>"
                         style="display:<?php echo $_ba_idx === 0 ? 'block' : 'none'; ?>;">
                        <?php /* ad_code is admin-configured HTML/JS and is intentionally output unescaped */ echo $_ba['ad_code']; ?>
                    </div>
                    <?php } ?>
                </div>
                <script>
                (function() {
                    var slides = document.querySelectorAll('#rotating-banner-ads .banner-ad-slide');
                    if (!slides || slides.length <= 1) return;
                    var current = 0;
                    function showNext() {
                        slides[current].style.display = 'none';
                        current = (current + 1) % slides.length;
                        slides[current].style.display = 'block';
                        var dur = parseInt(slides[current].getAttribute('data-duration'), 10);
                        if (isNaN(dur)) { dur = 5; }
                        if (dur === 0) return;
                        setTimeout(showNext, dur * 1000);
                    }
                    var initDur = parseInt(slides[0].getAttribute('data-duration'), 10);
                    if (isNaN(initDur)) { initDur = 5; }
                    if (initDur !== 0) {
                        setTimeout(showNext, initDur * 1000);
                    }
                })();
                </script>
                <?php } else { ?>
                <img src="images/logos/banner.webp" alt="gRPG" class="header-banner"/>
                <?php } ?>
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
