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

	// http://stackoverflow.com/questions/11166981/how-to-use-jquery-to-retrieve-ajax-search-results-for-wordpress
	// http://stackoverflow.com/questions/7136887/autocomplete-with-json-data-fetched-via-ajax
	// http://wordpress.stackexchange.com/questions/42891/how-to-create-live-autofill-search
	// var acs_action = 'bhaawp_company_search';
	jQuery("#house_search").autocomplete({
		source: function(req, response){
//			jQuery.getJSON(bhaawp.ajaxurl+'?callback=?action='+acs_action, req, response);
			jQuery.ajax({
				  url: bhaawp.ajaxurl,
				  dataType: 'json',
				  data: {
					  action:'bhaawp_house_search',
					  term:req.term
				  },
				  success: function( request ) {
					  //alert(request);
					  response( jQuery.map( request.matches, function( item ) {
							return {
								label: item.label,
								value: item.label,
								link: item.link
							}
						}));
					},
//					error:function(xhr,err){
//					    alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
//					    alert("responseText: "+xhr.responseText);
//					}
				});
		},
		minLength: 3,
		select: function(event, ui) {
			//alert( ui.item );
			window.location.href=ui.item.link;
		},
		open: function() {
			jQuery( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			jQuery( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}	
	});
});
