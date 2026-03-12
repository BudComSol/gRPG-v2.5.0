<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';

if (array_key_exists('fighter', $_GET)) {
    if (!csrf_check('cage_csrf', $_GET)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE, null, true);
    } elseif ($user_class->hospital != 0) {
        echo Message('<center><font size=2>Come back when your not warded!');
    } elseif ($user_class->jail != 0) {
        echo Message('<center><font size=2>Come back when your not in the cells!');
    } else {
        $fighter = $_GET['fighter'];
        // Check fighter exists and is not already taken
        $db->query('SELECT COUNT(boxnumber) FROM `luckyboxes` WHERE `boxnumber` = ? AND `playerid` = 0');
        $db->execute([$fighter]);
        $available = (int)$db->result();
        if ($available === 1) {
            // Check if current user already picked a fighter
            $db->query('SELECT COUNT(playerid) FROM `luckyboxes` WHERE `playerid` = ?');
            $db->execute([$user_class->id]);
            $alreadyPicked = (int)$db->result();
            if ($alreadyPicked !== 0) {
                echo Message('<center><font size=2>You have already picked a fighter!');
            } else {
                $db->query('UPDATE `luckyboxes` SET `playerid` = ? WHERE `boxnumber` = ? AND `playerid` = 0');
                $db->execute([$user_class->id, $fighter]);
                echo Message('<center><font size=2>You have picked : <font color=orange> ' . htmlspecialchars($fighter, ENT_QUOTES, 'UTF-8') . ' </font>for this fight.<br /></font><font size=2>You will be notified if they win.');
            }
        } else {
            echo Message('<center><font size=2>Sorry, that fighter is already taken.');
        }
    }
}
?>

<style type="text/css">

    .box {
        font-weight: bold;
        background-color: #000000;
        border: 1px solid #000000;
        padding: 3px;
    }

</style>

<tr>
    <td class="content-head">CageFighting</td>
</tr>
<tr>
    <td class="content-head">
     <br>
      <br>
        <center><img src="images/headers/cagefight.webp"/><br/><br/><font size=2>
                <br/>
                <center><font color=#99e6b3>They kick off every hour, just pick your fighter to
                    compete.<br/>If your fighter happens to be the last man standing after the bloodbath.<br/>You will pocket a very handsome <font
                            color=orange><b>$500,000</b></font> for your efforts.</font><br/><br/></center>
            </font>
    </td>
</tr>
<tr>
    <td class="contentcontent"><br/><br>
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <table width="100%" align="center" cellpadding="5px">
                        <tr>
                            <td class="content-head"><font size=2>Fighter</td>
                            <td class="content-head">
                                <center><font size=2>Chosen By
                            </td>
                        </tr>
                        <?php
                        $db->query('SELECT * FROM `luckyboxes` LIMIT 10');
                        $db->execute();
                        $leftFighters = $db->fetch();
                        $csrfToken = csrf_create('cage_csrf', false);
                        if ($leftFighters !== null) {
                            foreach ($leftFighters as $line) {
                                $boxnumber = $line['boxnumber'];
                                if (strlen((string)$boxnumber) == 1) {
                                    $boxnumber = "&nbsp;" . $line['boxnumber'] . "&nbsp;";
                                } else {
                                    $boxnumber = $line['boxnumber'];
                                }
                                if ($line['playerid'] != 0) {
                                    $boxes_user = new User($line['playerid']);
                                    $text = $boxes_user->formattedname;
                                } else {
                                    $text = "[<a href='plugins/cagefights.php?fighter=" . urlencode((string)$line['boxnumber']) . "&amp;cage_csrf=" . htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') . "'><font color=orange>Pick Fighter</font></a>]";
                                }
                                ?>
                                <tr>
                                    <td align="left" width="40%"><font size=2><font color=white><span
                                                        class="box"><?php echo $boxnumber; ?></span></td>
                                    <td align="center" width="60%"><font size=2><?php echo $text; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                    <br/><br/>
                </td>
                <td>
                    <table width="100%" align="center" cellpadding="5px">
                        <tr>
                            <td class="content-head"><font size=2>Fighter</td>
                            <td class="content-head">
                                <center><font size=2>Chosen By
                            </td>
                        </tr>
                        <?php
                        $db->query('SELECT * FROM `luckyboxes` LIMIT 10 OFFSET 10');
                        $db->execute();
                        $rightFighters = $db->fetch();
                        if ($rightFighters !== null) {
                            foreach ($rightFighters as $line) {
                                $boxnumber = $line['boxnumber'];
                                if (strlen((string)$boxnumber) == 1) {
                                    $boxnumber = "&nbsp;" . $line['boxnumber'] . "&nbsp;";
                                } else {
                                    $boxnumber = $line['boxnumber'];
                                }
                                if ($line['playerid'] != 0) {
                                    $boxes_user = new User($line['playerid']);
                                    $text = $boxes_user->formattedname;
                                } else {
                                    $text = "[<a href='plugins/cagefights.php?fighter=" . urlencode((string)$line['boxnumber']) . "&amp;cage_csrf=" . htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') . "'><font color=orange>Pick Fighter</font></a>]";
                                }
                                ?>
                                <tr>
                                    <td align="left" width="40%"><font size=2><font color=white><span
                                                        class="box"><?php echo $boxnumber; ?></span></td>
                                    <td align="center" width="60%"><font size=2><?php echo $text; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                    <br/><br/>
                </td>
            </tr>
        </table>

        <table width="100%" cellpadding="10" cellspacing="0" class="contentcontent">
            <tr>
                <td colspan="2" class="content-head">
                    <center>Last 10 Results</center>
                </td>
            </tr>
            <tr>
                <td class="content-head">
                    <center><font size="2">Winner</font></center>
                </td>
                <td class="content-head">
                    <center><font size="2">Fighter</font></center>
                </td>
            </tr>
            <?php
            $db->query('SELECT * FROM `cagewinners` ORDER BY `id` DESC LIMIT 10');
            $db->execute();
            $winners = $db->fetch();
            if ($winners !== null) {
                foreach ($winners as $line) {
                    $winner_user = new User($line['userid']);
                    $monkeyname = $line['monkeyname'];
                    ?>
                    <tr>
                        <td><center><font size="2"><?php echo $winner_user->formattedname; ?></font></center></td>
                        <td><center><font size="2"><?php echo htmlspecialchars($monkeyname, ENT_QUOTES, 'UTF-8'); ?></font></center></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
    </td>
</tr>
