<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/header.php';
$db->query('SELECT carid, name, image
    FROM cars
    INNER JOIN carlot ON carid = carlot.id
    WHERE userid = ?
    ORDER BY carid ');
$db->execute([$user_class->id]);
$rows = $db->fetch();
?><tr>
    <th class="content-head">Your Garage</th>
</tr>
<tr>
    <td class="content"><p>Here Is Where You Keep All Your Sweet Rides.</p></td>
</tr>
<tr>
    <td class="content">
        <table width="100" class="center">
            <tr><?php
if ($rows !== null) {
        $cnt = 1;
        foreach ($rows as $row) {
            ?><td width="25%">
                        <img src="<?php echo format($row['image']); ?>" width="100" height="100" style="border: 1px solid #333;" /><br />
                        <?php echo car_popup($row['name'], $row['carid']); ?>
                    </td><?php
        if (!($cnt % 4)) {
            echo '</tr><tr>';
        }
            ++$cnt;
        }
    } else {
        ?><p>There are no cars parked in the garage, find some.</p><?php
    }
?></tr>
        </table>
    </td>
</tr>
