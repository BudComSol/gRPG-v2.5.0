<?php
include 'header.php';
if ($User->gang < 1) {
    echo Message("<font size=2>No Gang...No Bar.</font>");
    include 'footer.php';
    die();
}
if ($User->hospital > 0) {
    echo Message("<font size=2>You are in the Hospital.</font>");
    include 'footer.php';
    die();
}
if ($User->jail > 0) {
    echo Message("<font size=2>You are in the cells</font>");
    include 'footer.php';
    die();
}
$gang = new Gang($User->gang);
if ($_GET['attack'] == "window") {
    $error = ($User->energypercent < 30) ? "<font size=2>You need to have at least 30% of your energy to attempt the bar." : $error;
    if (isset($error)) {
        echo Message($error);
        include 'footer.php';
        die();
    }
    $chance = rand(1, 5);
    if ($chance == '1') {
        $gangattack = $DBO->query("SELECT * FROM `gangattacks` WHERE `gang` = '" . $User->gang . "'");
        $gangattack2 = $DBO->row_count($gangattack);
        if ($gangattack2 < 1) {
            $result = $DBO->query("INSERT INTO `gangattacks`(`gang`, `no`) VALUES ('" . $User->gang . "', '3')");
        } else {
            $result = $DBO->query("UPDATE `gangattacks` SET `no` = no+3 WHERE `gang` = '" . $User->gang . "'");
        }
        $newenergy = $User->energy - floor(($User->energy / 100) * 10);
        $newbarpoints = $User->barpoints + 3;
        $result = $DBO->query("UPDATE `grpgusers` SET `energy` = '" . $newenergy . "', `barpoints` = '" . $newbarpoints . "' WHERE `id`='" . $User->id . "'");
        echo Message("<tr><td class='contentcontent'><center><font size=2>Your shot took out the window and  killed the thug inside.<br />You just earned <font color=orange>3 Points</font> towards your gangs total score.<br /><br /><a href='gangbar.php'>[Back to The Bar]</a><br /><br />");
        include 'footer.php';
        die();
    }
    if ($chance == '2') {
        $newenergy = $User->energy - floor(($User->energy / 100) * 10);
        $result = $DBO->query("UPDATE `grpgusers` SET `hospital` = '300', `hwhen` = '', `hhow` = '80',`energy` = '" . $newenergy . "' WHERE `id`='" . $User->id . "'");
        echo Message("<tr><td class='contentcontent'><center><font size=2>You failed.<br />A bullet clipped you while you took aim, its off to the ward and get stitches you go.<br /><br /><a href='gangbar.php'>[Back to The Bar]</a><br /><br />");
        include 'footer.php';
        die();
    }
    if ($chance == '3') {
        $gangattack = $DBO->query("SELECT * FROM `gangattacks` WHERE `gang` = '" . $User->gang . "'");
        $gangattack2 = $DBO->row_count($gangattack);
        if ($gangattack2 < 1) {
            $result = $DBO->query("INSERT INTO `gangattacks`(`gang`, `no`) VALUES ('" . $User->gang . "', '3')");
        } else {
            $result = $DBO->query("UPDATE `gangattacks` SET `no` = no+3 WHERE `gang` = '" . $User->gang . "'");
        }
        $newenergy = $User->energy - floor(($User->energy / 100) * 10);
        $newbarpoints = $User->barpoints + 3;
        $result = $DBO->query("UPDATE `grpgusers` SET `energy` = '" . $newenergy . "', `barpoints` = '" . $newbarpoints . "' WHERE `id`='" . $User->id . "'");
        echo Message("<tr><td class='contentcontent'><center><font size=2>Your shot took out the window and  killed the thug inside.<br />You just earned <font color=orange>3 Points</font> towards your gangs total score.<br /><br /><a href='gangbar.php'>[Back to The Bar]</a><br /><br />");
        include 'footer.php';
        die();
    }
    if ($chance == '4') {
        $gangattack = $DBO->query("SELECT * FROM `gangattacks` WHERE `gang` = '" . $User->gang . "'");
        $gangattack2 = $DBO->row_count($gangattack);
        if ($gangattack2 < 1) {
            $result = $DBO->query("INSERT INTO `gangattacks`( `gang`, `no`) VALUES ('" . $User->gang . "', '3')");
        } else {
            $result = $DBO->query("UPDATE `gangattacks` SET `no` = no+3 WHERE `gang` = '" . $User->gang . "'");
        }
        $newenergy = $User->energy - floor(($User->energy / 100) * 10);
        $newbarpoints = $User->barpoints + 3;
        $result = $DBO->query("UPDATE `grpgusers` SET `energy` = '" . $newenergy . "', `barpoints` = '" . $newbarpoints . "' WHERE `id`='" . $User->id . "'");
        echo Message("<tr><td class='contentcontent'><center><font size=2>Your shot took out the window and  killed the thug inside.<br />You just earned <font color=orange>3 Points</font> towards your gangs total score.<br /><br /><a href='gangbar.php'>[Back to The Bar]</a><br /><br />");
        include 'footer.php';
        die();
    }
    if ($chance == '5') {
        $gangattack = $DBO->query("SELECT * FROM `gangattacks` WHERE `gang` = '" . $User->gang . "'");
        $gangattack2 = $DBO->row_count($gangattack);
        if ($gangattack2 < 1) {
            $result = $DBO->query("INSERT INTO `gangattacks`(`gang`, `no`) VALUES ('" . $User->gang . "', '3')");
        } else {
            $result = $DBO->query("UPDATE `gangattacks` SET `no` = no+3 WHERE `gang` = '" . $User->gang . "'");
        }
        $newenergy = $User->energy - floor(($User->energy / 100) * 10);
        $newbarpoints = $User->barpoints + 3;
        $result = $DBO->query("UPDATE `grpgusers` SET `energy` = '" . $newenergy . "', `barpoints` = '" . $newbarpoints . "' WHERE `id`='" . $User->id . "'");
        echo Message("<tr><td class='contentcontent'><center><font size=2>Your shot took out the window and killed the thug inside.<br />You just earned <font color=orange>3 Points</font> towards your gangs total score.<br /><br /><a href='gangbar.php'>[Back to The Bar]</a><br /><br />");
        include 'footer.php';
        die();
    }
}
if ($_GET['attack'] == "door") {
    $error = ($User->energypercent < 25) ? "<font size=2>You need to have at least 25% of your energy to attempt the Bar." : $error;
    if (isset($error)) {
        echo Message($error);
        include 'footer.php';
        die();
    }
    $chance = rand(1, 3);
    if ($chance == '1') {
        $gangattack = $DBO->query("SELECT * FROM `gangattacks` WHERE `gang` = '" . $User->gang . "'");
        $gangattack2 = $DBO->row_count($gangattack);
        if ($gangattack2 < 1) {
            $result = $DBO->query("INSERT INTO `gangattacks`(`gang`, `no`) VALUES ('" . $User->gang . "', '6')");
        } else {
            $result = $DBO->query("UPDATE `gangattacks` SET `no` = no+6 WHERE `gang` = '" . $User->gang . "'");
        }
        $newenergy = $User->energy - floor(($User->energy / 100) * 10);
        $newbarpoints = $User->barpoints + 6;
        $result = $DBO->query("UPDATE `grpgusers` SET `energy` = '" . $newenergy . "', `barpoints` = '" . $newbarpoints . "' WHERE `id`='" . $User->id . "'");
        echo Message("<tr><td class='contentcontent'><center><font size=2>You smashed the door to pieces and killed the guy guarding it<br />You just earned <font color=orange>6 Points</font> Towards your gangs total score.<br /><br /><a href='gangbar.php'>[Back to The Bar]</a><br /><br />");
        include 'footer.php';
        die();
    }
    if ($chance == '2') {
        $newenergy = $User->energy - floor(($User->energy / 100) * 20);
        $result = $DBO->query("UPDATE `grpgusers` SET `hospital` = '300', `hwhen` = '', `hhow` = '80',`energy` = '" . $newenergy . "' WHERE `id`='" . $User->id . "'");
        echo Message("<tr><td class='contentcontent'><center><font size=2>You failed.<br />A bullet clipped you while you took aim, its off to the ward for stitches you go.<br /><br /><a href='gangbar.php'>[Back to The Bar]</a><br /><br />");
        include 'footer.php';
        die();
    }
    if ($chance == '3') {
        $gangattack = $DBO->query("SELECT * FROM `gangattacks` WHERE `gang` = '" . $User->gang . "'");
        $gangattack2 = $DBO->row_count($gangattack);
        if ($gangattack2 < 1) {
            $result = $DBO->query("INSERT INTO `gangattacks`(`gang`, `no`) VALUES ('" . $User->gang . "', '6')");
        } else {
            $result = $DBO->query("UPDATE `gangattacks` SET `no` = no+6 WHERE `gang` = '" . $User->gang . "'");
        }
        $newenergy = $User->energy - floor(($User->energy / 100) * 10);
        $newbarpoints = $User->barpoints + 6;
        $result = $DBO->query("UPDATE `grpgusers` SET `energy` = '" . $newenergy . "', `barpoints` = '" . $newbarpoints . "' WHERE `id`='" . $User->id . "'");
        echo Message("<tr><td class='contentcontent'><center><font size=2>You smashed the door to pieces and killed the guy guarding it<br />You just earned <font color=orange>3 Points</font> Towards your gangs total score.<br /><br /><a href='gangbar.php'>[Back to The Bar]</a><br /><br />");
        include 'footer.php';
        die();
    }
}
?>
<tr>
    <td class='contenthead'>The Gang Bar</td>
</tr>
<tr>
    <td class='contentcontent'><br/>
        <center>
            <img src="images/barscene.webp" width="350" alt="Bar Scene"/><br/><br/>
            <font size=2>Score as many points as possible over a 1 hour period.<br/>Windows will
                award a lower score but are easier to succeed.<br/>The door will award higher scores but you will die
                more often.<br/>Gang with the high score over the hour wins $250,000 for its
                vaults.<br><br><p>Just click your target below and good luck.</p>
                <table width="450" border="0">
                    <tr>
                        <th scope="col"><font size=2><b>Top Window</th>
                        <th scope="col"><font size=2><b>Boarded Window</th>
                        <th scope="col"><font size=2><b>Main Door</th>                                                
                    </tr>
                    <tr>
                        <th scope="col"><a href='gangbar.php?attack=window'><img src="images/topwindow.png" width="71" height="113" BORDER='0'/></a></th>
                        <th scope="col"><a href='gangbar.php?attack=window'><img src="images/bottomwindow.png" width="71" height="113" BORDER='0'/></a></th>
                        <th scope="col"><a href='gangbar.php?attack=door'><img src="images/bardoor.png" width="71" height="113" BORDER='0'/></a></th>                        
                    </tr>
                </table>
                <br/><br/>
            </font></center>
    </td>
</tr>
<?php
$result = $DBO->query("SELECT * FROM `goth` where gang > 0 ORDER BY `time`  DESC LIMIT 10");

echo '<table width=100% cellpadding=4 cellspacing=0 class=contentcontent>
	<tr><td colspan="3" class="contenthead">Last 10 Winners</td></tr>
	<tr><td><font size=2>Winner</td><td><font size=2>How long Ago</td><td><font size=2>Score</td></tr>';
while ($line = $DBO->fetch_array($result, MYSQL_ASSOC)) {
    $l_winner = new Gang($line['gang']);
    print "<font size=2><tr><td><font size=2>" . $l_winner->formattedname . "</td><td><font size=2>" . howlongago($line['time']) . "</td><td><font size=2>" . prettynum($line['kills']) . " Points</td></tr>";
}
echo '</td></tr>';
include 'footer.php';
?>
