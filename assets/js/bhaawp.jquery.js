//var $j = jQuery.noConflict();
jQuery(document).ready( function(){
	
	jQuery(function() {
		jQuery("#bhaa_runner_dateofbirth").datepicker({
			defaultDate: '-30y', 
			dateFormat: 'dd/mm/yy',
			yearRange: '1900:1994',
			changeYear: 'true',
			changeMonth: 'true',
			maxDate: '-18y' 
			});
	});
	
	jQuery.post(
		    // see tip #1 for how we declare global javascript variables
		    bhaawp.ajaxurl,
		    {
		        // here we declare the parameters to send along with the request
		        // this means the following action hooks will be fired:
		        // wp_ajax_nopriv_myajax-submit and wp_ajax_myajax-submit
		        action : 'bhaawp-submit',
		 
		        // other parameters can be added along with "action"
		        //postID : postID
		    },
		    function( response ) {
		        alert( response );
		    }
		);
});
