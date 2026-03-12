<?php
declare(strict_types=1);
define('STAFF_FILE', true);
require_once __DIR__.'/../inc/header.php';
if (!$user_class->admin) {
    echo Message('You don\'t have access', 'Access Denied', true);
}
$nums2 = array_unique(['money', 'strength', 'defense', 'speed', 'level', 'rmdays', 'points', 'hookers', 'nerve', 'bank', 'experience', 'hp', 'energy', 'awake', 'admin_lvl']);
foreach ($nums2 as $what) {
    $_POST[$what] = (isset($_POST[$what]) && ctype_digit(str_replace(',', '', $_POST[$what]))) ? str_replace(',', '', $_POST[$what]) : 0;
}
$errors = [];
if (isset($_POST['edituser'])) {
    if (!csrf_check('edituser_token', $_POST)) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
        return;
    }
    $_POST['username'] = (isset($_POST['username']) && is_string($_POST['username'])) ? strip_tags(trim($_POST['username'])) : '';
    if (empty($_POST['username'])) {
        $errors[] = 'You didn\'t select a valid player';
    }
    $id = Get_ID($_POST['username']);
    if (!$id) {
        $errors[] = 'The player you selected doesn\'t exist';
    }
    if (!in_array((int)$_POST['admin_lvl'], [0, 1, 2, 3, 4], true)) {
        $errors[] = 'Invalid admin level';
    }
    if (!count($errors)) {
        $target = new User($id);
        $jail_value = isset($_POST['jailed']) ? 2147483647 : 0;
        $hospital_value = isset($_POST['hospitalized']) ? 2147483647 : 0;
        $db->trans('start');
        $db->query('UPDATE users SET money = ?, bank = ?, level = ?, experience = ?, hp = ?, energy = ?, nerve = ?, awake = ?, strength = ?, defense = ?, speed = ?, points = ?, rmdays = ?, hookers = ?, admin = ?, jail = ?, hospital = ? WHERE id = ?');
        $db->execute([
            $_POST['money'],
            $_POST['bank'],
            $_POST['level'],
            $_POST['experience'],
            $_POST['hp'],
            $_POST['energy'],
            $_POST['nerve'],
            $_POST['awake'],
            $_POST['strength'],
            $_POST['defense'],
            $_POST['speed'],
            $_POST['points'],
            $_POST['rmdays'],
            $_POST['hookers'],
            $_POST['admin_lvl'],
            $jail_value,
            $hospital_value,
            $id,
        ]);
        $db->trans('end');
        echo Message('User '.$target->formattedname.' has been updated');
    } else {
        display_errors($errors);
    }
}
$editrow = null;
$editCsrfFailed = false;
if (isset($_GET['user'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !csrf_check('csrf', $_GET)) {
        $editCsrfFailed = true;
    } else {
        $_GET['user'] = ctype_digit($_GET['user']) ? (int)$_GET['user'] : 0;
        if (empty($_GET['user'])) {
            $errors[] = 'Invalid input.';
        } else {
            $db->query('SELECT id, username, money, bank, level, experience, hp, energy, nerve, awake, strength, defense, speed, points, rmdays, hookers, admin, jail, hospital FROM users WHERE id = ?');
            $db->execute([$_GET['user']]);
            if (!$db->count()) {
                $errors[] = 'Invalid user!';
            }
            $editrow = $db->fetch(true);
        }
    }
}
?>
<tr><th class="content-head">Edit User<?php if ($editrow) { echo ': ' . htmlspecialchars($editrow['username'], ENT_QUOTES, 'UTF-8'); } ?></th></tr>
<?php if (count($errors)) {
    display_errors($errors);
} ?>
<tr><td class="content"><?php
    if ($editCsrfFailed) {
        echo Message(SECURITY_TIMEOUT_MESSAGE);
        return;
    }
    if (isset($_GET['user'])) {
        if ($editrow) { ?>
            <form method="POST" action="plugins/edit_citizens.php" class="pure-form pure-form-aligned">
                <?php echo csrf_create('edituser_token'); ?>
                <input type="hidden" name="username" value="<?php echo htmlspecialchars($editrow['username'], ENT_QUOTES, 'UTF-8'); ?>" />
                <fieldset>
                    <legend>Editing: <?php echo htmlspecialchars($editrow['username'], ENT_QUOTES, 'UTF-8'); ?></legend>
                    <div class="pure-control-group">
                        <label for="money">Money</label>
                        <input type="text" name="money" id="money" value="<?php echo (int)$editrow['money']; ?>" size="15" maxlength="20" />
                    </div>
                    <div class="pure-control-group">
                        <label for="bank">Bank</label>
                        <input type="text" name="bank" id="bank" value="<?php echo (int)$editrow['bank']; ?>" size="15" maxlength="20" />
                    </div>
                    <div class="pure-control-group">
                        <label for="level">Level</label>
                        <input type="text" name="level" id="level" value="<?php echo (int)$editrow['level']; ?>" size="10" maxlength="10" />
                    </div>
                    <div class="pure-control-group">
                        <label for="experience">Experience</label>
                        <input type="text" name="experience" id="experience" value="<?php echo (int)$editrow['experience']; ?>" size="15" maxlength="20" />
                    </div>
                    <div class="pure-control-group">
                        <label for="hp">HP</label>
                        <input type="text" name="hp" id="hp" value="<?php echo (int)$editrow['hp']; ?>" size="10" maxlength="10" />
                    </div>
                    <div class="pure-control-group">
                        <label for="energy">Energy</label>
                        <input type="text" name="energy" id="energy" value="<?php echo (int)$editrow['energy']; ?>" size="10" maxlength="10" />
                    </div>
                    <div class="pure-control-group">
                        <label for="nerve">Nerve</label>
                        <input type="text" name="nerve" id="nerve" value="<?php echo (int)$editrow['nerve']; ?>" size="10" maxlength="10" />
                    </div>
                    <div class="pure-control-group">
                        <label for="awake">Awake</label>
                        <input type="text" name="awake" id="awake" value="<?php echo (int)$editrow['awake']; ?>" size="10" maxlength="10" />
                    </div>
                    <div class="pure-control-group">
                        <label for="strength">Strength</label>
                        <input type="text" name="strength" id="strength" value="<?php echo (int)$editrow['strength']; ?>" size="10" maxlength="10" />
                    </div>
                    <div class="pure-control-group">
                        <label for="defense">Defense</label>
                        <input type="text" name="defense" id="defense" value="<?php echo (int)$editrow['defense']; ?>" size="10" maxlength="10" />
                    </div>
                    <div class="pure-control-group">
                        <label for="speed">Speed</label>
                        <input type="text" name="speed" id="speed" value="<?php echo (int)$editrow['speed']; ?>" size="10" maxlength="10" />
                    </div>
                    <div class="pure-control-group">
                        <label for="points">Points</label>
                        <input type="text" name="points" id="points" value="<?php echo (int)$editrow['points']; ?>" size="10" maxlength="10" />
                    </div>
                    <div class="pure-control-group">
                        <label for="rmdays">RM Days</label>
                        <input type="text" name="rmdays" id="rmdays" value="<?php echo (int)$editrow['rmdays']; ?>" size="10" maxlength="10" />
                    </div>
                    <div class="pure-control-group">
                        <label for="hookers">Hookers</label>
                        <input type="text" name="hookers" id="hookers" value="<?php echo (int)$editrow['hookers']; ?>" size="10" maxlength="10" />
                    </div>
                    <div class="pure-control-group">
                        <label for="jailed">Jailed (Permanent)</label>
                        <input type="checkbox" name="jailed" id="jailed" value="1"<?php echo $editrow['jail'] == 2147483647 ? ' checked' : ''; ?> />
                    </div>
                    <div class="pure-control-group">
                        <label for="hospitalized">Hospital (Permanent)</label>
                        <input type="checkbox" name="hospitalized" id="hospitalized" value="1"<?php echo $editrow['hospital'] == 2147483647 ? ' checked' : ''; ?> />
                    </div>
                    <div class="pure-control-group">
                        <label for="admin_lvl">Admin Level</label>
                        <select name="admin_lvl" id="admin_lvl">
                            <option value="0"<?php echo $editrow['admin'] == 0 ? ' selected' : ''; ?>>Regular (0)</option>
                            <option value="1"<?php echo $editrow['admin'] == 1 ? ' selected' : ''; ?>>Admin (1)</option>
                            <option value="2"<?php echo $editrow['admin'] == 2 ? ' selected' : ''; ?>>Staff (2)</option>
                            <option value="3"<?php echo $editrow['admin'] == 3 ? ' selected' : ''; ?>>President (3)</option>
                            <option value="4"<?php echo $editrow['admin'] == 4 ? ' selected' : ''; ?>>Congress (4)</option>
                        </select>
                    </div>
                </fieldset>
                <div class="pure-controls">
                    <button type="submit" name="edituser" class="pure-button pure-button-primary">Save Changes</button>
                </div>
            </form>
        <?php } else {
            display_errors($errors);
        }
    } else { ?>
        <form method="GET" class="pure-form pure-form-aligned">
            <?php echo csrf_create(); ?>
            <div class="pure-control-group">
                <label for="user">Select User</label>
                <?php echo listCitizens(); ?>
            </div>
            <div class="pure-controls">
                <button name="who" type="submit" class="pure-button pure-button-primary">Select User</button>
            </div>
        </form><?php
    } ?>
</td></tr>
