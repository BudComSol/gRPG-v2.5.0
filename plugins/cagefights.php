<?php
include 'header.php';

if ($_GET['fighter'] != "") {
    if ($User->hospital == 0) {
        if ($User->jail == 0) {
            $result = $DBO->query("SELECT * FROM `luckyboxes` WHERE `boxnumber` = '" . $_GET['fighter'] . "' AND `playerid`='0'");
            $rows = $DBO->row_count($result);
            if ($rows == 1) {
                $result = $DBO->query("SELECT * FROM `luckyboxes` WHERE `playerid` = '" . $User->id . "'");
                $rows = $DBO->row_count($result);
                if ($rows != 0) {
                    echo Message("<center><font size=2>You have already picked a fighter!");
                } else {
                    $result = $DBO->query("UPDATE `luckyboxes` SET `playerid` = '" . $User->id . "' WHERE `boxnumber` = '" . $_GET['fighter'] . "'");
                    echo Message("<center><font size=2>You have picked : <font color=orange> " . $_GET['fighter'] . " </font>for this fight.<br /></font><font size=2>You will be notified if they win.");
                }
            } else {
                echo Message("<center><font size=2>Sorry, that fighter is already taken.");
            }
        } else {
            echo Message("<center><font size=2>Come back when your not in the cells!");
        }
    } else {
        echo Message("<center><font size=2>Come back when your not warded!");
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
    <td class="contenthead">CageFighting</td>
</tr>
<tr>
    <td class="contenthead">
     <br>
      <br>
        <center><img src="images/cagefight.webp"/><br/><br/><font size=2>
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
                            <td class="contenthead"><font size=2>Fighter</td>
                            <td class="contenthead">
                                <center><font size=2>Chosen By
                            </td>
                        </tr>
                        <?php
                        $result2 = $DBO->query("SELECT * FROM `luckyboxes` LIMIT 10");
                        while ($line = $DBO->fetch_array($result2, MYSQL_ASSOC)) {
                            $boxnumber = $line['boxnumber'];
                            if (strlen($boxnumber) == 1) {
                                $boxnumber = "&nbsp;" . $line['boxnumber'] . "&nbsp;";
                            } else {
                                $boxnumber = $line['boxnumber'];
                            }
                            $boxes_user = UserFactory::getInstance()->getUser($line['playerid']);
                            if ($line['playerid'] != 0) {
                                $text = $boxes_user->formattedname;
                            } else {
                                $text = "[<a href='cagefights.php?fighter=" . $line['boxnumber'] . "'><font color=orange>Pick Fighter</font></a>]";
                            }
                            ?>
                            <tr>
                                <td align="left" width="40%"><font size=2><font color=white><span
                                                    class="box"><?php echo $boxnumber; ?></span></td>
                                <td align="center" width="60%"><font size=2><?php echo $text; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <br/><br/>
                </td>
                <td>
                    <table width="100%" align="center" cellpadding="5px">
                        <tr>
                            <td class="contenthead"><font size=2>Fighter</td>
                            <td class="contenthead">
                                <center><font size=2>Chosen By
                            </td>
                        </tr>
                        <?php
                        $result2 = $DBO->query("SELECT * FROM `luckyboxes` LIMIT 10,10");
                        while ($line = $DBO->fetch_array($result2, MYSQL_ASSOC)) {
                            $boxnumber = $line['boxnumber'];
                            if (strlen($boxnumber) == 1) {
                                $boxnumber = "&nbsp;" . $line['boxnumber'] . "&nbsp;";
                            } else {
                                $boxnumber = $line['boxnumber'];
                            }
                            $boxes_user = UserFactory::getInstance()->getUser($line['playerid']);
                            if ($line['playerid'] != 0) {
                                $text = $boxes_user->formattedname;
                            } else {
                                $text = "[<a href='cagefights.php?fighter=" . $line['boxnumber'] . "'><font color=orange>Pick Fighter</font></a>]";
                            }
                            ?>
                            <tr>
                                <td align="left" width="40%"><font size=2><font color=white><span
                                                    class="box"><?php echo $boxnumber; ?></span></td>
                                <td align="center" width="60%"><font size=2><?php echo $text; ?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                    <br/><br/>
                </td>
            </tr>

            <?php
$result = $DBO->query("SELECT * FROM `cagewinners` ORDER BY `id` DESC LIMIT 10");
?>

<table width="100%" cellpadding="10" cellspacing="0" class="contentcontent">
    <tr>
        <td colspan="2" class="contenthead">
            <center>Last 10 Results</center>
        </td>
    </tr>
    <tr>
        <td class="contenthead">
            <center><font size="2">Winner</font></center>
        </td>
        <td class="contenthead">
            <center><font size="2">Fighter</font></center>
        </td>
    </tr>

    <?php while ($line = $DBO->fetch_array($result, MYSQL_ASSOC)): ?>
        <?php
            $name = UserFactory::getInstance()->getUser($line['userid']);
            $monkeyname = $line['monkeyname'];
        ?>
        <tr>
            <td><center><font size="2"><?php echo ($name->formattedname); ?></font></center></td>
            <td><center><font size="2"><?= htmlspecialchars($monkeyname) ?></font></center></td>
        </tr>
    <?php endwhile; ?>
</table>
<?php

            include 'footer.php';
            ?>

