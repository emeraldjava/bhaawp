<div class="wrap">
<h2>BHAA Racetec Export</h2>
<?php
if(isset( $_GET['result'])){
	echo "<div id='message' class='updated fade'><p><strong>Text message sent</strong></p></div>";
}
?>
<form method="post" action="<?php echo admin_url( 'admin.php' ) ?>">
	<input type="hidden" name="action" value="bhaa_admin_racetec_export" />
	<?php wp_nonce_field( 'bhaa_admin_racetec_export' ); ?>
    <table class="form-table">
        <tr valign="top">
        <td>
			<input type="submit" value="Export Racetec File" class="button-primary"/>
		</td>
    </table>
</form>
<hr/>
<div><p>This is the sample text.</p></div>
</div>