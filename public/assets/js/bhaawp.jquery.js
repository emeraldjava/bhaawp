jQuery(document).ready( function() {	
	// table sorter
	jQuery('.race_results').tablesorter({
		sortList: [[0,0]],
    	stringTo: "min",
        headers: {
        	1: {sorter: false},
        	3: {sorter: false},
            5: {sorter: false}
        }
	});
	
	jQuery('.runner_results').tablesorter({
		sortList: [[1,1]]
	}); 

	// firstname 8
	// secondname 10
	
	// username 14
	// http://stackoverflow.com/questions/4932746/autofill-input-field-with-value-of-another-input-field
	jQuery(".um_field_14").focusout(
		function() 
		{
			//alert('user name out : '+jQuery(this).val());
			if(jQuery(this).text()=="")
			{
				jQuery(this).val(jQuery(".um_field_8").val()+'.'+jQuery(".um_field_10").val());
			}
			//alert('username defined '+jQuery(this).val());
			//console.log(jQuery(this).val());
		}
	);

	// email 15
	jQuery(".um_field_15").focusout(
		function() {
			//alert('Handler for .focus() called.');
			jQuery(this).val(jQuery(".um_field_8").val()+'.'+jQuery(".um_field_10").val()+'@x.com');
		}
	);
	
	debug = function (log_txt) {
	    if (typeof window.console != 'undefined') {
	        console.log(log_txt);
	    }
	}
		
	// http://stackoverflow.com/questions/11166981/how-to-use-jquery-to-retrieve-ajax-search-results-for-wordpress
	// http://stackoverflow.com/questions/7136887/autocomplete-with-json-data-fetched-via-ajax
	// http://wordpress.stackexchange.com/questions/42891/how-to-create-live-autofill-search
	// http://wordpress.stackexchange.com/questions/56343/template-issues-getting-ajax-search-results/56349#56349
	
	// var acs_action = 'bhaawp_company_search';
	jQuery("#house_search").autocomplete({
		source: function(req, response){
//			jQuery.getJSON(bhaawp.ajaxurl+'?callback=?action='+acs_action, req, response);
			jQuery.ajax({
				  url: bhaaAjax.ajaxurl,
				  dataType: 'json',
				  data: {
					  action:'bhaawp_house_search',
					  term:req.term
				  },
				  success: function( request ) {
					  console.log(request);
					  alert(request);
					  response(	jQuery.each(request.matches, function(item){
						return {
									label: item.label, 
									value: item.label, 
									link: item.link }
								}
							)	  
					  );
					  
//					  response( jQuery.map( request.matches, function( item ) {
//							return {
//								label: item.label,
//								value: item.label,
//								link: item.link
//							}
//						}));
					},
//					error:function(xhr,err){
//					    alert("readyState: "+xhr.readyState+"\nstatus: "+xhr.status);
//					    alert("responseText: "+xhr.responseText);
//					}
				});
		},
		minChars: 3,
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