<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
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
                    <a href="plugins/pharmacy.php">Pharmacy</a><br />
                    <a href="plugins/spendpoints.php">Point Store</a><br />
                    <a href="plugins/itemmarket.php">Item Market</a><br />                    
                    <a href="plugins/pointmarket.php">Points Market</a><br />
                    <a href="plugins/store.php">Weapon Sales</a><br />
                    <a href="plugins/astore.php">Armor Emporium</a><br />                    
                    <?php echo $user_class->city == 2 ? '<a href="plugins/carlot.php">Big Bob\'s Used Car Lot</a>' : ''; ?>
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
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Punting</h3><br />
                    <a href="plugins/5050game.php">50/50</a><br />
                    <a href="plugins/lottery.php">Lottery</a><br />
                    <a href="plugins/slots.php">Slot Machine</a><br />
                    
                </td>
            </tr>
            <tr>
                <td class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Uptown</h3><br />
                    <a href="plugins/events.php">Events <!_-events-_!></a><br />
                    <a href="plugins/pms.php">Mailbox <!_-mail-_!></a><br />                                        
                    <a href="plugins/inventory.php">Inventory</a><br />                    
                    <a href="plugins/house.php">Move House</a><br />
                    <a href="plugins/fields.php">Manage Land</a>
                </td>
                <td class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Travel</h3><br />
                    <a href="plugins/drive.php">Drive</a><br />
                    <a href="plugins/bus.php">Bus Station</a><br />
                    <a href="plugins/expguide.php">Experience Guide</a><br />                    
                </td>
                <td class="top" style="padding-bottom:10px;">
                    <h3 style="padding:0;margin:0;font-size:1.4em;">Downtown</h3><br />
                    <a href="bank.php">Bank</a><br />
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
