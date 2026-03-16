<?php

include 'header.php';
if ($User->gamble > 0) {
    echo Message("<font size=2>You can only have one go per day, come back tomorrow.");
    include 'footer.php';
    die();
}
if ($User->jail > 0) {
    echo Message("<font size=2>You cannot gamble, you are in the cells.</font>");
    include 'footer.php';
    die();
}

$time = time();
if ($_GET['bet'] == 500) {
    if ($User->money < 500) {
        echo Message("<font size=2>You need $500 in your hand to be able to try this gamble.</font>");
        include 'footer.php';
        die();
    }
    $chance = rand(1, 3);
    if ($chance == "1") {
        echo Message("<center><font size=2><br />Well done you have won $500.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money + 500;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Won $500')");
    }
    if ($chance == "2") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $500.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 500;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 500')");
    }
    if ($chance == "3") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $500.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 500;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 500')");
    }
}
if ($_GET['bet'] == 1000) {
    if ($User->money < 1000) {
        echo Message("<font size=2>You need $1,000 in your hand to be able to try this gamble.</font>");
        include 'footer.php';
        die();
    }
    $chance = rand(1, 3);
    if ($chance == "1") {
        echo Message("<center><font size=2><br />Well done you have won $1,000<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money + 1000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Won 1k ')");
    }
    if ($chance == "2") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $1,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 1000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 1 k')");
    }
    if ($chance == "3") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $1,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 1000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 1k ')");
    }

}
if ($_GET['bet'] == 2500) {
    if ($User->money < 2500) {
        echo Message("<font size=2>You need $2,500 in your hand to be able to try this gamble.</font>");
        include 'footer.php';
        die();
    }
    $chance = rand(1, 4);
    if ($chance == "1") {
        echo Message("<center><font size=2><br />Well done you have won $2,500<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money + 2500;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Won 2.500 ')");
    }
    if ($chance == "2") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $2,500.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 2500;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 2.500 ')");
    }
    if ($chance == "3") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $2,500.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 2500;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 2.500 ')");
    }
    if ($chance == "4") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $2,500.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 2500;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 2.500 ')");
    }

}
if ($_GET['bet'] == 50000) {
    if ($User->money < 50000) {
        echo Message("<font size=2>You need $50,000 in your hand to be able to try this gamble.</font>");
        include 'footer.php';
        die();
    }
    $chance = rand(1, 5);
    if ($chance == "1") {
        echo Message("<center><font size=2><br />Well done you have won $50,000<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money + 50000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Won 50k ')");
    }
    if ($chance == "2") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $50,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 50000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 50k')");
    }
    if ($chance == "3") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $50,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 50000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 50k ')");
    }
    if ($chance == "4") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $50,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 50000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 50k ')");
    }
    if ($chance == "5") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $50,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 50000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 50k ')");
    }
}

if ($_GET['bet'] == 500000) {
    if ($User->money < 500000) {
        echo Message("<font size=2>You need $500,000 in your hand to be able to try this gamble.</font>");
        include 'footer.php';
        die();
    }
    $chance = rand(1, 5);
    if ($chance == "1") {
        echo Message("<center><font size=2><br />Well done you have won $500,000<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money + 500000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Won')");
    }
    if ($chance == "2") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $500,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 500000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost')");
    }
    if ($chance == "3") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $500,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 500000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost')");
    }
    if ($chance == "4") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $500,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 500000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost')");
    }
    if ($chance == "5") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $500,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 500000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost')");
    }
}

if ($_GET['bet'] == 5000000) {
    if ($User->money < 5000000) {
        echo Message("<font size=2>You need $5,000,000 in your hand to be able to try this gamble.</font>");
        include 'footer.php';
        die();
    }
    $chance = rand(1, 5);
    if ($chance == "1") {
        echo Message("<center><font size=2><br />Well done you have won $5,000,000<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money + 5000000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Won 5 million')");
    }
    if ($chance == "2") {
        echo Message("<center><font size=2><br />Gutted ... you just lost lost $5,000,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 5000000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 5 million')");
    }
    if ($chance == "3") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $5,000,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 5000000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 5 million')");
    }
    if ($chance == "4") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $5,000,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 5000000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 5 million')");
    }
    if ($chance == "5") {
        echo Message("<center><font size=2><br />Gutted ... you just lost your $5,000,000.<br /><br /></font><br /><br /> </font>");
        $newmoney = $User->money - 5000000;
        $newgamble = $User->gamble + 1;
        $result = $DBO->query("UPDATE grpgusers SET `money` = '" . $newmoney . "', `gamble` = '" . $newgamble . "' WHERE `id` = '" . $User->id . "' ");
        $result = $DBO->query("INSERT INTO `million` (`userid`,`timestamp`, `text`)" . "VALUES ('" . $User->id . "','" . $time . "',  'Lost 5 million')");
    }
}


?>

<tr>
    <td class="contenthead">Fools Gamble</td>
</tr>

<tr>
    <td class="contentprofile">
        <center>
            <br/><br/>
            <img src="images/bigcash.png" alt="bigcash" BORDER='0'/> <br/><br/><font size=2>Are you a lucky person?<br/>You
                can take one of these bets daily.<br/>Make your choice and have the cash in your hand<br/>If
                you win you double your stake, if you lose you may cry.<br/><br/>
                <a href='foolsgamble.php?bet=500'><font color=orange><font size=3>[ 500  ]</a>
                <a href='foolsgamble.php?bet=1000'><font color=orange><font size=3>[ 1000 ]</a>
                <a href='foolsgamble.php?bet=2500'><font color=orange><font size=3>[ 2500 ]</a>
                <a href='foolsgamble.php?bet=50000'><font color=orange><font size=3>[ 50 k] </a>
                <a href='foolsgamble.php?bet=500000'><font color=orange><font size=3>[ 500 k] </a>
                <a href='foolsgamble.php?bet=5000000'><font color=orange><font size=3>[ 5 mil] </a>
                <br/><br/><br/>
                <a href='city.php'><font size=3>[F^%& Off .... I'm Keeping My Cash]</a><br/><br/>


    </td>
</tr>

<?php
$result = $DBO->query("SELECT * FROM `million` ORDER BY `timestamp` DESC LIMIT 10");

echo '<table width=100% cellpadding=4 cellspacing=0 class=contentcontent>
	<tr><td colspan="3" class="contenthead">Last 10 Fools Results</td></tr>
	<tr><td class="contenthead"><font size=2>Thug</td><td class="contenthead"><font size=2>Date</td><td class="contenthead"><font size=2>Outcome</td></tr>';
while ($line = $DBO->fetch_array($result, MYSQL_ASSOC)) {

    $l_winner = UserFactory::getInstance()->getUser($line['userid']);
    $text = ($line['text']);
    print "<tr><td><font size=2>" . $l_winner->formattedname . "</td><td><font size=2>" . date(F . " " . d . ",  " . g . ":" . i . ":" . sa, $line['timestamp']) . "</td><td><font size=2>" . $text . "</td></tr>";
}
echo '</td></tr>';
include 'footer.php';
?>
