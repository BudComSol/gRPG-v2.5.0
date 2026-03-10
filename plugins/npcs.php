<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
// Regenerate NPC HP if regen time has passed
$db->query('UPDATE npcs SET hp = max_hp, last_defeated = 0 WHERE last_defeated > 0 AND (UNIX_TIMESTAMP() - last_defeated) >= hp_regen_time');
$db->execute();
// Fetch all enabled NPCs in the player's city
$db->query('SELECT id, name, description, image, strength, defense, speed, hp, max_hp, level, money, can_mug, can_attack FROM npcs WHERE enabled = 1 AND city = ? ORDER BY level ASC');
$db->execute([$user_class->city]);
$npcs = $db->fetch();
?><tr>
    <th class="content-head">NPCs &amp; Robots</th>
</tr>
<tr>
    <td class="content">
        <p>These are the NPCs and robots roaming <?php echo format($user_class->cityname); ?>. You can attack or mug them — but be careful, some of them fight back!</p>
    </td>
</tr>
<tr>
    <td class="content">
        <?php if (empty($npcs)) { ?>
            <p>There are no NPCs or robots in your city at this time.</p>
        <?php } else {
            $csrfg = csrf_create('csrfg', false);
            foreach ($npcs as $npc) {
                $defeated    = ($npc['hp'] <= 0);
                $npc_img     = (!empty($npc['image']) && $npc['image'] !== 'images/noimage.png') ? htmlspecialchars($npc['image'], ENT_QUOTES, 'UTF-8') : 'images/noimage.png';
                $npc_name    = htmlspecialchars($npc['name'], ENT_QUOTES, 'UTF-8');
                $npc_desc    = htmlspecialchars($npc['description'], ENT_QUOTES, 'UTF-8');
                $hp_pct      = $npc['max_hp'] > 0 ? max(0, min(100, (int)(($npc['hp'] / $npc['max_hp']) * 100))) : 0;
                ?>
            <table width="100%" class="pure-table pure-table-horizontal" style="margin-bottom:15px;">
                <thead>
                    <tr><th colspan="2"><?php echo $npc_name; ?> <small>(Level <?php echo (int)$npc['level']; ?>)</small></th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="120" style="text-align:center;vertical-align:top;">
                            <img src="<?php echo $npc_img; ?>" alt="<?php echo $npc_name; ?>" width="100" height="100" style="border:1px solid #333;" /><br />
                            <?php if ($npc['can_attack']) { ?><span style="color:red;" title="This NPC can attack you">&#9760; Lethal</span><?php } ?>
                            <?php if ($npc['can_mug'])    { ?><span style="color:orange;" title="This NPC can mug you"> &#128178; Mugger</span><?php } ?>
                        </td>
                        <td style="vertical-align:top;">
                            <p><?php echo $npc_desc; ?></p>
                            <table class="pure-table" style="width:100%;max-width:400px;">
                                <tr><td>HP</td><td>
                                    <div class="bar_a" style="min-width:150px;"><div class="bar_b bar_b_pink" style="width:<?php echo $hp_pct; ?>%;"></div></div>
                                    <?php echo (int)$npc['hp']; ?> / <?php echo (int)$npc['max_hp']; ?>
                                </td></tr>
                                <tr><td>Strength</td><td><?php echo (int)$npc['strength']; ?></td></tr>
                                <tr><td>Defense</td><td><?php echo (int)$npc['defense']; ?></td></tr>
                                <tr><td>Speed</td><td><?php echo (int)$npc['speed']; ?></td></tr>
                                <tr><td>Cash on Hand</td><td><?php echo prettynum((int)$npc['money'], true); ?></td></tr>
                            </table>
                            <br />
                            <?php if ($defeated) { ?>
                                <em>This NPC has been defeated and is recovering.</em>
                            <?php } else { ?>
                                <a href="plugins/npc_attack.php?npc=<?php echo (int)$npc['id']; ?>&amp;csrfg=<?php echo $csrfg; ?>" class="pure-button pure-button-primary">&#9876; Attack</a>
                                &nbsp;
                                <a href="plugins/npc_mug.php?npc=<?php echo (int)$npc['id']; ?>&amp;csrfg=<?php echo $csrfg; ?>" class="pure-button" style="background:#e67e22;color:#fff;">&#128178; Mug</a>
                            <?php } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php }
        } ?>
    </td>
</tr>
