jQuery(document).ready( function() {
	jQuery("a#bhaa_league_populate").bind("click", function(event) {
		//event.preventDefault();
		var url = jQuery(this).attr("href");
		alert("Now I want to call this page: " + url);
	});
});