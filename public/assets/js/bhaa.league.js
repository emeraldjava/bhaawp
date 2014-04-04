jQuery(document).ready( function() {
	jQuery("a#bhaa_league_populate").bind("click", function(event) {
		event.preventDefault();
		var url = jQuery(this).attr("href");
		
		var p = url.split('?');
	    var action = p[0];
	    var params = p[1];
	    
		jQuery.post( action, params);
	});
});