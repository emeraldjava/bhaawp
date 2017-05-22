<div class="wrap">
<h2>BHAA Admin Page</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'bhaa' ); ?>
    <?php do_settings_sections( 'bhaa' ); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">bhaa_registration_token</th>
            <td>
                <input type="text" name="bhaa_registration_token" value="<?php echo get_option('bhaa_registration_token'); ?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">bhaa_bookings_enabled</th>
            <td>
                <input name="bhaa_bookings_enabled" type="radio" value="1" <?php checked( '1', get_option('bhaa_bookings_enabled')); ?> >Enabled</input>
                <input name="bhaa_bookings_enabled" type="radio" value="0" <?php checked( '0', get_option('bhaa_bookings_enabled')); ?> >Disabled</input>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>

<h2>BHAA Membership Status</h2>
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
                'action' => 'bhaa_admin_export_csv', // as defined in the hidden page
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

<h2>BHAA User ID Moving</h2>
<div>Auto Increment <?php echo RunnerAdmin::get_instance()->getAutoIncrementValue();?></div>
<div>Max Runner <?php echo RunnerAdmin::get_instance()->getMaxRunnerValue();?></div>
<div>Next Runner ID <?php echo RunnerAdmin::get_instance()->getNextRunnerId();?></div>
<table border="1">
    <tbody>
    <tr>
        <th>Row</th>
        <th>ID</th>
        <th>Name</th>
        <th>Status</th>
        <th>New Membership ID</th>
    </tr>
    <?php
    $count=1;
    foreach($idRunners as $row) {
        $link = add_query_arg(
            array(
                'action'=>'bhaa_runner_move_action',
                'delete'=>$row->ID//,
               // 'id' => $nextRunnerId
            ),
            admin_url('admin.php')
        );
        echo sprintf('<tr><td>%d</td><td><a target="_blank" href="%s">%d</a></td><td>%s</td><td>%s</td><td><a target="_blank" href="%s">Move %d to %d</a></td></tr>',
            $count++,
            home_url().'/runner/?id='.$row->ID,
            $row->ID,$row->display_name,$row->status,$link,$row->ID,$nextRunnerId);
    }
    ?>
    </tbody>
</table>


<?php
$link = admin_url('admin.php?action=bhaa_send_email');
//echo '<a href='.$link.'>Send Email</a>';
?>
</div>