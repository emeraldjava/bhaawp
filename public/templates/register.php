<?php
/**
 * Template Name: BHAA Raceday Register
 * A template used to demonstrate how to include the template using this plugin.
 */
$file = get_option('bhaa_members_file_date');
wp_register_script(
		'bhaa_members',
		plugins_url('/assets/js/'.$file,dirname(__FILE__)),
		array('jquery'),
		null,
		false
);
wp_enqueue_script('bhaa_members');

wp_register_script(
	'bhaa-raceday',
	plugins_url('/assets/js/bhaa-raceday.js',dirname(__FILE__))
);
wp_enqueue_script('bhaa-raceday');

include_once 'header.php';
?>
<div class="panel-heading">
	<h3 class="panel-title">Register BHAA Members</h3>
</div>

<div class="panel-body">
		<div class="row">
			<div class="ui-widget input-group col-md-12">
				<input type="text" id="memberfilter" class="search-query form-control" placeholder="Search by Name or ID"/>
			</div>
		</div>
		<div class="row">
				<!-- http://stackoverflow.com/questions/23775272/bootstrap-modal-before-form-submit -->
				<?php echo wp_get_form('registerform'); ?>
		</div>

		<div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
							Confirm BHAA Runner Details
					</div>
					<div class="modal-body">
						Are you sure you want to submit the following details?
						<!-- We display the details entered by the user here -->
						<table class="table">
							<tr>
								<th>First Name</th>
								<td id="fname"></td>
							</tr>
							<tr>
								<th>Last Name</th>
								<td id="lname"></td>
							</tr>
						</table>
					</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<a href="#" id="modalsubmit" class="btn btn-success success">Enter Race</a>
				</div>
			</div>
		</div>
</div>

<?php
include_once 'footer.php';
?>
