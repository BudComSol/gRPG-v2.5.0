<?php
declare(strict_types=1);
if (!defined('GRPG_INC')) {
    exit;
}
if (!isset($db)) {
    require_once __DIR__.'/dbcon.php';
}
$stats = new User_Stats();
if (!defined('LOAD_TIME_END')) {
    define('LOAD_TIME_END', microtime_float());
}
$year = date('Y');
$totaltime = defined('LOAD_TIME_START') ? round(LOAD_TIME_END - LOAD_TIME_START, 3) : 0.01;
ob_start(); ?>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="827">
    <tr>
        <td height="20" colspan="2" align="center" class="content">
            <br>
            <a href="plugins/citizens.php"><?php echo $stats->playerstotal; ?> Total Citizens</a>&nbsp; | &nbsp;
            <a href="plugins/online.php"><?php echo $stats->playersloggedin; ?> Citizen<?php echo s($stats->playersloggedin); ?> Online</a>&nbsp; | &nbsp;
            <a href="plugins/24hour.php"><?php echo $stats->playersonlineinlastday; ?> Citizen<?php echo s($stats->playersonlineinlastday); ?> Online (24 Hours)</a> |<br /><br />
            This page was generated in <?php echo format($totaltime, 3); ?> seconds            
        </td>
    </tr>
</table>
<table width="100%">
    <tr>
        <td>
            <table class="topbar">
                <tr>
                    <td>gRPG © ● 2007 - 2026 ● All Rights Reserved</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html><?php
if(ob_get_level() > 0) {
    ob_end_flush();
}
