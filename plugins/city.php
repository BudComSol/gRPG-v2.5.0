<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';

// --- NPC proactive encounter: chance of being mugged/attacked when entering the city ---
if (!$user_class->jail && !$user_class->hospital) {
    // Regenerate NPC HP if regen time has passed
    $db->query('UPDATE npcs SET hp = max_hp, last_defeated = 0 WHERE last_defeated > 0 AND (UNIX_TIMESTAMP() - last_defeated) >= hp_regen_time AND city = ?');
    $db->execute([$user_class->city]);
    // Pick one random aggressive NPC in the player's city with HP > 0
    $db->query('SELECT id, name, strength, defense, speed, hp, money, can_mug, can_attack FROM npcs WHERE enabled = 1 AND city = ? AND hp > 0 AND (can_mug = 1 OR can_attack = 1) ORDER BY RAND() LIMIT 1');
    $db->execute([$user_class->city]);
    if ($db->count()) {
        $enc_npc = $db->fetch(true);
        // 20% chance of an encounter per city visit
        if (mt_rand(1, 100) <= 20) {
            $enc_name = htmlspecialchars($enc_npc['name'], ENT_QUOTES, 'UTF-8');
            if ($enc_npc['can_mug'] && $enc_npc['speed'] > $user_class->speed) {
                // NPC mugs the player
                $mugamt = (int)floor($user_class->money / 10);
                if ($mugamt > 0) {
                    $db->trans('start');
                    $db->query('UPDATE users SET money = GREATEST(money - ?, 0) WHERE id = ?');
                    $db->execute([$mugamt, $user_class->id]);
                    $db->query('UPDATE npcs SET money = money + ? WHERE id = ?');
                    $db->execute([$mugamt, (int)$enc_npc['id']]);
                    $db->trans('end');
                    Send_Event($user_class->id, 'You were mugged by the NPC '.$enc_name.' for '.prettynum($mugamt, true).'.', 0);
                }
            } elseif ($enc_npc['can_attack']) {
                // NPC attacks the player
                $yourhp  = $user_class->hp;
                $theirhp = (int)$enc_npc['hp'];
                $wait    = $user_class->speed > $enc_npc['speed'] ? 1 : 0;
                $limit   = 50;
                $turns   = 0;
                $npc_won = false;
                while ($yourhp > 0 && $theirhp > 0 && $turns < $limit) {
                    ++$turns;
                    $dmg = max(1, $enc_npc['strength'] - $user_class->moddeddefense);
                    if ($wait == 0) {
                        $yourhp -= $dmg;
                    } else {
                        $wait = 0;
                    }
                    if ($yourhp > 0) {
                        $pdmg    = max(1, $user_class->moddedstrength - $enc_npc['defense']);
                        $theirhp -= $pdmg;
                    }
                    if ($yourhp <= 0) {
                        $npc_won = true;
                        break;
                    }
                    if ($theirhp <= 0) {
                        break;
                    }
                }
                if ($npc_won) {
                    $moneylost = (int)floor($user_class->money / 10);
                    $db->trans('start');
                    $db->query('UPDATE users SET money = GREATEST(money - ?, 0), hwho = 0, hhow = \'npc_attacked\', hwhen = ?, hospital = 1200, battlelost = battlelost + 1 WHERE id = ?');
                    $db->execute([$moneylost, date('g:i:sa'), $user_class->id]);
                    $db->query('UPDATE npcs SET money = money + ? WHERE id = ?');
                    $db->execute([$moneylost, (int)$enc_npc['id']]);
                    $db->trans('end');
                    Send_Event($user_class->id, 'You were attacked and hospitalized by the NPC '.$enc_name.' while entering the city. They stole '.prettynum($moneylost, true).' from you.', 0);
                } else {
                    // Player survived/won — just update HP
                    $db->query('UPDATE users SET hp = ? WHERE id = ?');
                    $db->execute([max(0, $yourhp), $user_class->id]);
                    if ($theirhp <= 0) {
                        $db->query('UPDATE npcs SET hp = 0, last_defeated = UNIX_TIMESTAMP() WHERE id = ?');
                        $db->execute([(int)$enc_npc['id']]);
                    } else {
                        $db->query('UPDATE npcs SET hp = ? WHERE id = ?');
                        $db->execute([$theirhp, (int)$enc_npc['id']]);
                    }
                }
            }
        }
    }
}

$db->query('SELECT id FROM users WHERE city = ? ORDER BY (strength + speed + defense) DESC LIMIT 3');
$db->execute([$user_class->city]);
$rows = $db->fetch();
$i = 1;
$leaders = [];
if (!empty($rows)) {
    foreach ($rows as $row) {
        $leaders[$i] = new User($row['id']);
        ++$i;
    }
}
?><tr>
    <th class="content-head"><?php echo $user_class->cityname; ?></th>
</tr>
<tr>
    <td class="content">
        <img src="images/headers/city.png" alt="gRPG City Header">
    </td>
</tr>
<tr>
    <th class="content-head">Badass Citizens of <?php echo format($user_class->cityname); ?></th>
</tr>
<tr>
    <td class="content">
        <div class="city"><?php
for ($i = 1; $i <= 3; ++$i) {
    if (isset($leaders[$i])) {
        ?><div class="box<?php echo $i; ?>">
            <span><img class="medals-img" height="50" width="50" src="images/medals/<?php echo ordinal($i); ?>.png" /></span><br />
            <span><?php echo formatImage($leaders[$i]->avatar); ?></span><br />
            <span><?php echo $leaders[$i]->formattedname; ?></span><br />
            <span>Level: <?php echo format($leaders[$i]->level); ?></span><br />
            <span><?php echo !$leaders[$i]->gang ? '<br />' : $leaders[$i]->formattedgang; ?></span>
        </div><?php
    }
}
?></div>
    </td>
</tr>
<tr>
    <th class="content-head">The World Is Your Oyster</th>
</tr>
<tr>
    <td class="content">
        <table width="100%" class="pure-table pure-table-horizontal">
            <tr>
                <td width="33%" class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Markets</h3><br />                    
                    <a href="plugins/spendpoints.php">Point Store</a><br />
                    <a href="plugins/itemmarket.php">Item Market</a><br />                    
                    <a href="plugins/pointmarket.php">Points Market</a><br />
                    <a href="plugins/store.php">Weapon Sales</a><br />
                    <a href="plugins/astore.php">Armor Emporium</a><br />                    
                </td>
                <td width="34%" class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Town Hall</h3><br />
                    <a href="plugins/halloffame.php">HoF</a><br />
                    <a href="plugins/worldstats.php">World Stats</a><br />                     
                    <a href="plugins/citizens.php">Citizens List</a><br />
                    <a href="plugins/search.php">Citizen Search</a><br />
                    <a href="plugins/online.php">Citizens Online</a><br />                    
                </td>
                <td width="33%" class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Pot Luck</h3><br />
                    <a href="plugins/5050game.php">50/50</a><br />
                    <a href="plugins/lottery.php">Lottery</a><br />
                    <a href="plugins/slots.php">Slot Machine</a><br />
                    <a href="plugins/carlot.php">Bob's Used Cars</a>
                </td>
            </tr>
            <tr>
                <td class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Uptown</h3><br />
                    <a href="plugins/events.php">Events <!_-events-_!></a><br />
                    <a href="plugins/pms.php">Mailbox <!_-mail-_!></a><br />                                        
                    <a href="plugins/inventory.php">Inventory</a><br />                    
                    <a href="plugins/fields.php">Manage Land</a>
                </td>
                <td class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Travel</h3><br />
                    <a href="plugins/drive.php">Drive</a><br />                    
                    <a href="plugins/bus.php">Bus Station</a><br />
                    <a href="plugins/house.php">Move House</a><br />
                    <a href="plugins/expguide.php">Experience Guide</a><br />                    
                </td>
                <td class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Downtown</h3><br />
                    <a href="bank.php">Bank</a><br />
                    <a href="plugins/pharmacy.php">Pharmacy</a><br />
                    <a href="plugins/viewstaff.php">Game Staff</a><br />                                         
                    <a href="plugins/downtown.php">Search Downtown</a><br />                  
                </td>
            </tr>  
            <td class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Southside</h3><br />
                    <a href="plugins/gang_list.php">Gangs</a><br />                    
                    <a href="<?php echo !$user_class->gang ? 'plugins/create' : 'plugins/'; ?>gang.php">Your Gang</a><br />                    
                    <a href="plugins/jobs.php">Jobs Center</a><br />                                        
                    <a href="plugins/realestate.php">Real Estate Agency</a>
                </td>
            <td class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Back Alley</h3><br />
                    <a href="plugins/spylog.php">Spy Log</a><br />
                    <a href="plugins/refer.php">Referrals</a><br />
                    <a href="plugins/garage.php">Your Garage</a><br />
                    <a href="plugins/buydrugs.php">Shady Dude</a><br /> 
                    <a href="plugins/whorehouse.php">Whorehouse</a><br />
                    <a href="plugins/npcs.php">NPCs &amp; Robots</a><br />                  
                </td>                
                <td class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Northside</h3><br />
                    <a href="plugins/newspaper.php">Newspaper</a><br />
                    <a href="plugins/portfolio.php">View Portfolio</a><br />
                    <a href="plugins/brokerage.php">Brokerage Firm</a><br />
                    <a href="plugins/viewstocks.php">View Stock Market</a><br />                    
                </td>
            </tr>
        </table>
    </td>
</tr>
