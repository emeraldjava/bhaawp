<div class="wrap">
<!-- http://jaskokoyn.com/2013/03/26/wordpress-admin-forms/ -->
<h2>BHAA Text Submit Page</h2>
<?php
if(isset( $_GET['result'])){
	echo "<div id='message' class='updated fade'><p><strong>Text message sent</strong></p></div>";
}
?>
<form method="post" action="<?php echo admin_url( 'admin.php' ) ?>">
	<input type="hidden" name="action" value="bhaa_admin_send_text" />
	<?php wp_nonce_field( 'bhaa_admin_send_text' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">bhaa_annual_text_from</th>
        <td><input type="text" name="bhaa_annual_text_from" value=""/></td>
        </tr>
        <tr valign="top">
        <th scope="row">bhaa_annual_text_to</th>
        <td><input type="text" name="bhaa_annual_text_to" value=""/></td>
        </tr>
        <tr valign="top">
        <th scope="row">bhaa_annual_text_message</th>
        <td><input type="text" name="bhaa_annual_text_message" value=""/></td>
        </tr>
        <tr valign="top">
        <td>
			<input type="submit" value="Send Text" class="button-primary"/>
		</td>
    </table>
</form>
</div>