<div class="wrap">
<h2>BHAA Race Master</h2>
<table border="1">
    <tbody>
    <tr>
        <th>Membership Status</th>
        <th>Count</th>
        <th>Export</th>
    </tr>
    <?php
    foreach($membershipStatus as $row) {

        $link = add_query_arg(
            array(
                'action' => 'bhaa_admin_racemaster_export_csv', // as defined in the hidden page
                'status' => $row->status
            ),
            admin_url('admin.php')
        );

        echo sprintf('<tr><td>%1$s</td><td>%2$s</td>
            <td>
                <a href="%3$s">Export CSV %1$s Members</a>
            </td>
            </tr>
            ',
            $row->status,$row->count,$link);
    }
    ?>
    </tbody>
</table>

<?php
$link = add_query_arg( array('action' => 'bhaa_admin_racemaster_preregistered'), admin_url('admin.php'));
echo sprintf('<a href="%s">Export Pre-Registered Event Runners</a>',$link);
?>

</div>