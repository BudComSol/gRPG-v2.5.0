<?php
declare(strict_types=1);
http_response_code(404);
if (!defined('GRPG_INC')) {
    define('GRPG_INC', true);
}
require_once __DIR__.'/inc/nliheader.php';
?><tr>
    <th class="content-head">404 - Page Not Found</th>
</tr>
<tr>
    <td class="content center">
        <img src="images/404error.svg" alt="404 Error" style="max-width:300px; margin: 20px auto; display:block;" />
        <h2>Oops! The page you are looking for does not exist.</h2>
        <p>It may have been moved, deleted, or you may have typed the address incorrectly.</p>
        <p><a href="/">Return to Home</a></p>
    </td>
</tr>
<tr>
    <td>
        <table class="topbar">
            <tr>
                <td>gRPG © ● 2007 - 2026 ● All Rights Reserved</td>
            </tr>
        </table>
    </td>
</tr>
<?php require_once __DIR__.'/inc/nlifooter.php'; ?>
