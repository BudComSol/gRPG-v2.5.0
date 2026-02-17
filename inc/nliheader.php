<?php
declare(strict_types=1);
if (!defined('GRPG_INC')) {
    define('GRPG_INC', true);
}
require_once __DIR__.'/dbcon.php';
if ((defined('CAPTCHA_REGISTRATION') && CAPTCHA_REGISTRATION === true) || (defined('CAPTCHA_LOGIN') && CAPTCHA_LOGIN === true) || (defined('CAPTCHA_FORGOT_PASSWORD') && CAPTCHA_FORGOT_PASSWORD === true)) {
    require_once __DIR__.'/securimage/securimage.php';
    $securimage = new Securimage();
}
$time = date('F d, Y g:i:sa');
$siteURL = getenv('SITE_URL');
ob_start(); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head><?php
if ($siteURL !== false && $siteURL !== '') {
    ?>
    <base href="<?php echo rtrim($siteURL, '/').'/'; ?>"/>
    <?php
    }
    ?>
    <title>gRPG - A Full Stack Game Engine</title>
    <meta name="description" content="gRPG is a full stack game engine with which to build your own RPG, MMORPG or PBBG game.">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" type="text/css" media="all" href="/css/login.css"/>
    <link rel="stylesheet" type="text/css" media="all" href="/css/style.css"/>    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/g/pure@0.6.2(buttons-min.css+grids-min.css+forms-min.css)"
    integrity="sha384-+YK1ur0Mr74WEZWTMC6oMb5fojhkGm6EpjgVheKlE9urf2PbykYP7MxdwPpruQB8" crossorigin="anonymous"/>
    <link rel="stylesheet" href="/css/custom-styles.css">      
    <script src="/js/cookie-consent.js"></script>
</head>
<body>
<!-- Cookie Consent Banner -->
<div id="cookie-consent-banner">
    <div class="cookie-content">
        <div class="cookie-message">
            We use cookies to ensure you get the best experience on our website. 
            These include essential cookies for site functionality and session management. 
            By continuing to use this site, you consent to our use of cookies. 
            <a href="<?php echo ($siteURL !== false && $siteURL !== '') ? '' : '/'; ?>inc/privacy.php">Learn more</a>
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
                        <td width="50%" class="center-img"><img src="/images/logos/logo.png" alt="gRPG Main Logo"/></td>

                    </tr>
                </table>
            </div>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top" width="150">
                        <div>
                            <div class="headbox">Menu</div>
                            <a href="/" class="leftmenu">Home</a>
                            <a href="login.php" class="leftmenu">Login</a>
                            <a href="register.php" class="leftmenu">Register</a>                            
                            <a href="inc/privacy.php" class="leftmenu">Privacy Policy</a>
                            <a href="inc/terms.php" class="leftmenu">Terms of Service</a>
                            <a href="forgot.php" class="leftmenu">Account Recovery</a>
                        </div>
                    </td>
                    <td valign="top">
                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                            <tr>
                                <td width="10"></td>
                                <td valign="top" class="mainbox">
                                    <table class="content">
