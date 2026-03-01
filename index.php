<?php
declare(strict_types=1);
require_once __DIR__.'/inc/header.php';
?><tr>
    <th class="content-head">General</th>
</tr>
<tr>
    <td class="content">
        <table width="100%" class="pure-table pure-table-horizontal">
            <tr>
                <th width="12.5%">Name:</th>
                <td width="37.5%"><?php echo $user_class->formattedname; ?></td>
                <th width="12.5%">Respected:</th>
                <td width="37.5%"><?php echo $user_class->formattedrespected; ?></td>
            </tr>
            <tr>
                <th>Role:</th>
                <td><?php echo htmlspecialchars($user_class->type); ?></td>
                <th>Level:</th>
                <td><?php echo format($user_class->level); ?></td>
            </tr>
            <tr>
                <th>Money:</th>
                <td><?php echo prettynum($user_class->money, true); ?></td>
                <th>Bank:</th>
                <td><?php echo prettynum($user_class->bank, true); ?></td>
            </tr>
            <tr>
                <th>EXP:</th>
                <td><?php echo $user_class->formattedexp; ?></td>
                <th>Work EXP:</th>
                <td><?php echo format($user_class->workexp); ?></td>
            </tr>
            <tr>
                <th>Whores:</th>
                <td><?php echo format($user_class->hookers); ?></td>
                <th>Marijuana:</th>
                <td><?php echo format($user_class->marijuana); ?></td>
            </tr>
            <tr>
                <th>HP:</th>
                <td><?php echo $user_class->formattedhp; ?></td>
                <th>Energy:</th>
                <td><?php echo $user_class->formattedenergy; ?></td>
            </tr>
            <tr>
                <th>Awake:</th>
                <td><?php echo $user_class->formattedawake; ?></td>
                <th>Nerve:</th>
                <td><?php echo $user_class->formattednerve; ?></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <th class="content-head">Attributes</th>
</tr>
<tr>
    <td class="content">
        <table width="100%" class="pure-table pure-table-horizontal">
            <tr>
                <th width="12.5%">Strength:</th>
                <td width="37.5%"><?php echo format($user_class->strength); ?></td>
                <th width="12.5%">Defense:</th>
                <td width="37.5%"><?php echo format($user_class->defense); ?></td>
            </tr>
            <tr>
                <th>Speed:</th>
                <td><?php echo format($user_class->speed); ?></td>
                <th>Total:</th>
                <td><?php echo format($user_class->totalattrib); ?></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <th class="content-head">Battle Statistics</th>
</tr>
<tr>
    <td class="content">
        <table width="100%" class="pure-table pure-table-horizontal">
            <tr>
                <th width="12.5%">Won:</th>
                <td width="37.5%"><?php echo format($user_class->battlewon); ?></td>
                <th width="12.5%">Lost:</th>
                <td width="37.5%"><?php echo format($user_class->battlelost); ?></td>
            </tr>
            <tr>
                <th>Total:</th>
                <td><?php echo format($user_class->battletotal); ?></td>
                <th>$ Gain:</th>
                <td><?php echo format($user_class->battlemoney); ?></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <th class="content-head">Crime Statistics</th>
</tr>
<tr>
    <td class="content">
        <table width="100%" class="pure-table pure-table-horizontal">
            <tr>
                <th width="12.5%">Succeeded:</th>
                <td width="37.5%"><?php echo format($user_class->crimesucceeded); ?></td>
                <th width="12.5%">Failed:</th>
                <td width="37.5%"><?php echo format($user_class->crimefailed); ?></td>
            </tr>
            <tr>
                <th>Total:</th>
                <td><?php echo format($user_class->crimetotal); ?></td>
                <th>$ Gain:</th>
                <td><?php echo format($user_class->crimemoney); ?></td>
            </tr>
        </table>
    </td>
</tr>


