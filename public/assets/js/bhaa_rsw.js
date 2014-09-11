jQuery(document).ready(function($) {
	
	jQuery("#runner_search").autocomplete({
		source: function(req, response){
			jQuery.ajax({
				  url: bhaa_rsw.ajax_url,
				  data: { action:'bhaawp_runner_search', term:req.term },
				  success: function( request ) {
					  	//debug('success'+request.matches);
						//alert(request);
						response(	
							jQuery.each(request.matches, function(item){
								return {label:item.label,link:item.link}
						})	  
						);
					}
//		,error:function(xhr,err) {
//		    debug("readyState: "+xhr.readyState+"\nstatus: "+xhr.status+"\n:responseText: "+xhr.responseText+" "+err);
//			}
		});
		},
		minChars: 4,
		select: function(event, ui) {
			//debug(ui.item.id);
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