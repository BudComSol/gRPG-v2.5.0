<?php
include "header.php";
$visit = $visits + 1;
$DBO->query("UPDATE `pagetrack` SET `visits`=visits+1 WHERE `page` = 'slaps'");
print"<tr><td class='contenthead'>Slaps</td></tr>
<tr><td class='contentprofile'>";

if (!$_GET['a']) {
    print"<form action='slap.php?a=slap' method='post'>
<b><font size=2><center>Fancy annoying someone with a slap?<br />Enter their ID here and send a Slap their way.</b><br /><b>Each slap costs you 1 point but its worth it, right?</b><br /><br /><center>
ID: <input type='text' class='inputa' name='ID' value='" . $_GET['target'] . "' maxlength='5' length='15' size='15' width='15' readonly/><br /><br>
<input type='submit' value='Send a Slap' STYLE='background-color:orange;color:black;border-color: #AFC7C7;'/></center>
</form><br /> <a href='city.php'>[No Thank You] </a></center><hr>

";
}
if ($_GET['a'] == slap) {
    if ($User->points < 1) {
        echo Message("You need to have at least 1 point to send a slap!");
        include 'footer.php';
        die();
    }

    $to = $_POST['ID'];
    $q = $DBO->query("SELECT id FROM grpgusers WHERE id='{$to}'");
    if ($DBO->row_count($q) == 0) {
        print "<br /><font size=2>You are trying to slap a non existent user.<br /><br /><hr> <a href='slap.php'>Back</a><hr>";
    } else if ($_POST['ID'] == $User->id) {
        print "<br /><font size=2>You cannot slap yourself!<br /><br /><hr> <a href='slap.php'>Back</a><hr>";
    } else if ($User->slapping > 99) {
        print "<br /><font size=2>You can only slap 100 times a day.<br /><br /><hr> <a href='slap.php'>Back</a><hr>";
    } else {
        $DBO->query("UPDATE grpgusers SET slapping=slapping+1, points=points-1 WHERE id = " . $User->id);
        $DBO->query("UPDATE grpgusers SET slapped=slapped+1 WHERE id = " . $_POST['ID']);
        $result = $DBO->query("INSERT INTO `allevents` (text)" . "VALUES ('" . $User->username . " just slapped someone')");

        $msg = rand(1, 48);
        if ($msg == 1) {
            $text = 'just showed you the papers proving your birth certificate is an apology from the condom factory';
        }
        if ($msg == 2) {
            $text = 'just slapped your bare ass!';
        }
        if ($msg == 3) {
            $text = 'just poked you in the eye!';
        }
        if ($msg == 4) {
            $text = 'just broke your fingers with a baseball bat!';
        }
        if ($msg == 5) {
            $text = 'just kicked you in the crotch!';
        }
        if ($msg == 6) {
            $text = 'just disconnected your cable services!';
        }
        if ($msg == 7) {
            $text = 'just rang your mother and told her where you keep your porn stash!';
        }
        if ($msg == 8) {
            $text = 'just removed all the labels from every can in your house';
        }
        if ($msg == 9) {
            $text = 'just left a horse head in your bed';
        }
        if ($msg == 10) {
            $text = 'just posted your nude pictures on facebook!';
        }
        if ($msg == 11) {
            $text = 'just took a piss in your shoes!';
        }
        if ($msg == 12) {
            $text = 'just released thousands of cockroaches into your home!';
        }
        if ($msg == 13) {
            $text = 'just caught you on the toilet...took photos...and sent them to everyone you know!';
        }
        if ($msg == 14) {
            $text = 'just sneezed in your face....your mouth was open....slime hits your teeth.....grim!';
        }
        if ($msg == 15) {
            $text = 'just drew a sun cream dick on your back whilst you were tanning!';
        }
        if ($msg == 16) {
            $text = 'just released woodworm into your house.!';
        }
        if ($msg == 17) {
            $text = 'just rang your boss and told him you said he was a wanker!';
        }
        if ($msg == 18) {
            $text = 'just showed you naked pics of your MUM ..bleach your eyes now !!!!';
        }
        if ($msg == 19) {
            $text = 'just dipped your hand in dog shit whilst you slept....then tickled your nose with a feather.!';
        }
        if ($msg == 20) {
            $text = 'just ate your liver .....with some fava beans and a nice chianti!';
        }
        if ($msg == 21) {
            $text = 'just swapped your lube for deep heat.!';
        }
        if ($msg == 22) {
            $text = 'just pissed in your water bottle.!';
        }
        if ($msg == 23) {
            $text = 'just shot a tazer at your ass.!';
        }
        if ($msg == 24) {
            $text = 'just licked their palm and wiped spit down your face.!';
        }
        if ($msg == 25) {
            $text = 'wipes snot on your sofa every time they visit you.!';
        }
        if ($msg == 26) {
            $text = 'just broke into your house and stuck your toothbrush up their ass...then put it back.....!';
        }
        if ($msg == 27) {
            $text = 'just left a massive gift in your toilet..it wont flush..unless you break its back with the toilet brush.!';
        }
        if ($msg == 28) {
            $text = 'just took a crap in your lunchbox.!';
        }
        if ($msg == 29) {
            $text = 'just mailed you an envelope full of their pubic hair.!';
        }
        if ($msg == 30) {
            $text = 'just punched you in the mouth';
        }
        if ($msg == 31) {
            $text = 'just spat in your BigMac!';
        }
        if ($msg == 32) {
            $text = 'just swapped your pile cream for chili paste!';
        }
        if ($msg == 33) {
            $text = 'just swapped ganja stash for dried tarragon!!!!!!';
        }
        if ($msg == 34) {
            $text = 'just told everyone you know that you have six toes!';
        }
        if ($msg == 35) {
            $text = 'just knocked out a duck....then told the park ranger it was you!';
        }
        if ($msg == 36) {
            $text = 'just hacked your pc....but when they saw your special film collection they left asap!';
        }
        if ($msg == 37) {
            $text = 'just stole your identity..you are now wanted for fraud in 6 countries!';
        }
        if ($msg == 38) {
            $text = 'just signed you up for a colonic irrigation!';
        }
        if ($msg == 39) {
            $text = 'just pushed you in a sewage pit!';
        }
        if ($msg == 40) {
            $text = 'just called into your work and told them you have aids!';
        }
        if ($msg == 41) {
            $text = 'just knocked up your mom with triplets!';
        }
        if ($msg == 42) {
            $text = 'just skinned your face and drove to New Mexico wearing it!';
        }
        if ($msg == 43) {
            $text = 'just hung a picture of your shit skid underwear on freeway advertisement!';
        }
        if ($msg == 44) {
            $text = 'just gave you a complete golden shower!';
        }
        if ($msg == 45) {
            $text = 'just stole your identity and spent your money on cocaine and hookers!';
        }
        if ($msg == 46) {
            $text = 'just slipped some concrete boots on you and threw you in a river!';
        }
        if ($msg == 47) {
            $text = 'just pulled down your pants in public!';
        }
        if ($msg == 48) {
            $text = 'just filled your pillows with their shaved pubic hair!';
        }
        if ($msg == 49) {
            $text = 'just attacked you ...he killed you...and fucked you in the ass whilst he did it!';
        }

        Send_Event($_POST['ID'], "" . mysql_real_escape_string($User->formattedname) . " $text");
        print "
<font size=2>You slapped user ID: {$_POST['ID']}";
    }
}
include 'footer.php';
?>
