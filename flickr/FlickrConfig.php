<div class="wrap">

<div id="icon-tools" class="icon32"></div>

<h2><?php echo __('Settings Flickr account ', 'flickr_plus'); ?></h2>

	<form name="editar_dados_flickr" id="editar_dados_flickr" action="options.php" enctype="multipart/form-data" method="post">

	<?php settings_fields( 'flickr_plus' ); ?>

	<table class="form-table">

		<tbody>

        <tr>

			<th valign="top" scope="row"><?php echo __('Username', 'flickr_plus'); ?></th>

			<td><input type="text" value="<?php echo get_option('flickr_plus_username'); ?>" name="flickr_plus_username" size="70"/></td>

		</tr>

        <tr>

			<th valign="top" scope="row"><?php echo __('User id', 'flickr_plus'); ?></th>

			<td><input type="text" value="<?php echo get_option('flickr_plus_user_id'); ?>" name="flickr_plus_user_id" size="70"/></td>

		</tr>

        <tr>

			<th valign="top" scope="row"><?php echo __('API KEY', 'flickr_plus'); ?></th>

			<td><input type="text" value="<?php echo get_option('flickr_plus_api_key'); ?>" name="flickr_plus_api_key" size="70"/></td>

		</tr>

        <tr>

			<th valign="top" scope="row"><?php echo __('SECRET', 'flickr_plus'); ?></th>

			<td><input type="text" value="<?php echo get_option('flickr_plus_secret'); ?>" name="flickr_plus_secret" size="70"/></td>	

		</tr>

		</tbody>

    </table>

    <p style="text-align: center;">

        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />

	</p>

    </form>

</div>