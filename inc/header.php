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
if (!array_key_exists('id', $_SESSION) || !$_SESSION['id']) {
    header('Location: login.php');
    exit;
}
// Check for logout
if (array_key_exists('logout', $_GET)) {
    session_destroy();
    header('Location: home.php');
    exit;
}
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
    ?><title><?php echo GAME_NAME; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" type="text/css" media="all" href="css/login.css"/>
    <link rel="stylesheet" type="text/css" media="all" href="css/style.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/g/pure@0.6.2(buttons-min.css+grids-min.css+forms-min.css)"
          integrity="sha384-+YK1ur0Mr74WEZWTMC6oMb5fojhkGm6EpjgVheKlE9urf2PbykYP7MxdwPpruQB8" crossorigin="anonymous"/>
</head>
<body>
<table bgcolor="#1E1E1E" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td>
            <table class="topbar">
                <tr>
                    <td>&gt; Server Time: <?php echo $time; ?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="pos1" height="55" valign="middle">
            <div class="topbox">
                <table width="800">
                    <tr>
                        <td width="50%" class="center"><img src="images/logos/logo.png" alt="GRPG"
                                                            style="height:150px;"/></td>

                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top" width="150"><?php
                        require_once __DIR__.'/menu.php';
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
