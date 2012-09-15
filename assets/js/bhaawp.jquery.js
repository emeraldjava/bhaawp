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
});

