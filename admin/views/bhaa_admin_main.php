<div class="wrap">
<h2>BHAA Admin Page</h2>
<form method="post" action="options.php">
    <?php settings_fields( 'bhaa' ); ?>
    <?php do_settings_sections( 'bhaa' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">bhaa_annual_event_id</th>
        <td><input type="text" name="bhaa_annual_event_id" value="<?php echo get_option('bhaa_annual_event_id'); ?>" /></td>
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
</div>