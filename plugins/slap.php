<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
$_GET['target'] = array_key_exists('target', $_GET) && ctype_digit($_GET['target']) ? $_GET['target'] : null;
if (array_key_exists('submit', $_POST)) {
    $errors = [];
    if (!csrf_check('csrf', $_POST)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
    }
    $_POST['theirid'] = array_key_exists('theirid', $_POST) && ctype_digit($_POST['theirid']) ? $_POST['theirid'] : null;
    if (empty($_POST['theirid'])) {
        $errors[] = '<p>You didn\'t enter a valid player ID.</p>';
    }
    if (!userExists($_POST['theirid'])) {
        $errors[] = '<p>That player doesn\'t exist.</p>';
    }
    if ($_POST['theirid'] == $user_class->id) {
        $errors[] = '<p>You can\'t slap yourself.</p>';
    }
    if ($user_class->points < 1) {
        $errors[] = '<p>You need at least 1 point to send a slap.</p>';
    }
    $db->query('SELECT slapping FROM users WHERE id = ?');
    $db->execute([$user_class->id]);
    $slapping = (int)$db->result();
    if ($slapping >= 100) {
        $errors[] = 'You can only slap 100 times per day';
    }
    if (count($errors)) {
        display_errors($errors);
    } else {
        $messages = [
            'just showed you papers proving your birth certificate is an apology from the condom factory!',
            'just slapped your bare ass!',
            'just poked you in the eye!',
            'just broke your fingers with a baseball bat!',
            'just kicked you in the crotch, now thats gotta hurt!',
            'just disconnected your cable services, krap!',
            'just rang your mother and told her where you keep your porn stash!',
            'just removed all the labels from every can in your house!',
            'just left a horse head in your bed!',
            'just posted your nude pictures on facebook!',
            'just took a piss in your shoes!',
            'just released thousands of cockroaches into your home!',
            'just caught you on the toilet, took photos and sent them to everyone you know!',
            'just sneezed in your face, your mouth was open and slime hits your teeth ... grim!',
            'just drew a sun cream dick on your back whilst you were tanning!',
            'just released woodworm into your house!',
            'just rang your boss and told him you said he was a wanker!',
            'just showed you naked pics of your MUM ... bleach your eyes now!',
            'just dipped your hand in dog shit whilst you slept, then tickled your nose with a feather!',
            'just ate your liver, with some fava beans and a nice chianti!',
            'just swapped your lube for deep heat, say what!',
            'just pissed in your water bottle!',
            'just shot a tazer at your ass!',
            'just licked their palm and wiped spit down your face!',
            'wipes snot on your sofa every time they visit you!',
            'just broke into your house and stuck your toothbrush up their ass, then put it back in the cup!',
            'just left a massive gift in your toilet, it wont flush unless you break its back with the toilet brush!',
            'just took a crap in your lunchbox!',
            'just mailed you an envelope full of their pubic hair!',
            'just punched you in the mouth!',
            'just spat in your BigMac!',
            'just swapped your pile cream for chili paste!',
            'just swapped ganja stash for dried tarragon!!',
            'just told everyone you know that you have six toes!',
            'just knocked out a duck, then told the park ranger it was you!',
            'just hacked your pc, but when they saw your special film collection they left asap!',
            'just stole your identity, you are now wanted for fraud in 6 countries!',
            'just signed you up for a colonic irrigation!',
            'just pushed you in a sewage pit!',
            'just called into your work and told them you have aids!',
            'just knocked up your mom with triplets!',
            'just skinned your face and drove to New Mexico wearing it!',
            'just hung a picture of your shit skid underwear on freeway advertisement!',
            'just gave you a complete golden shower!',
            'just stole your identity and spent your money on cocaine and hookers!',
            'just slipped some concrete boots on you and threw you in a river!',
            'just pulled down your pants in public!',
            'just filled your pillows with their shaved pubic hair!',
        ];
        $text = $messages[array_rand($messages)];
        $db->trans('start');
        $db->query('UPDATE users SET slapping = slapping + 1, points = GREATEST(points - 1, 0) WHERE id = ? AND points >= 1');
        $db->execute([$user_class->id]);
        $db->query('UPDATE users SET slapped = slapped + 1 WHERE id = ?');
        $db->execute([$_POST['theirid']]);
        $db->trans('end');
        Send_Event((int)$_POST['theirid'], '{extra} '.$text, $user_class->id);
        $target = new User((int)$_POST['theirid']);
        echo Message('You slapped '.$target->formattedname.'!');
    }
}
?><tr>
    <th class="content-head">Slap</th>
</tr>
<tr>
    <td class="content">
        <form action="plugins/slap.php" method="post" class="pure-form pure-form-aligned">
            <?php echo csrf_create(); ?>
            <fieldset>
                <div class="pure-control-group">
                    <label for="theirid">Player ID</label>
                    <input type="text" name="theirid" id="theirid" size="22" value="<?php echo format($_GET['target'] ?? ''); ?>" />
                </div>
            </fieldset>
            <div class="pure-controls">
                <button type="submit" name="submit" class="pure-button pure-button-primary">Send a Slap (Cost 1 Point)</button>
            </div>
        </form>
        <p>Each Slap Costs 1 Point And You Can Slap Up To 100 Times Per Day.</p>
    </td>
</tr>
