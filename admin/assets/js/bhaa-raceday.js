jQuery(document).ready( function() {
	jQuery("#memberfilter").autocomplete({
		source: bhaa_members,
		minLength: 3,
		source: function (request, response) {
		    var matcher = new RegExp(jQuery.ui.autocomplete.escapeRegex(request.term), "i");
		    response(jQuery.grep(bhaa_members, function(value) {
		        return matcher.test(value.label) || matcher.test(value.value);
		    }));
		},
		focus: function( event, ui ) {
			jQuery("#memberfilter").val(ui.item.label);
        	return false;
      	},
		select: function(event, ui) {
			jQuery("#bhaa_runner").val( ui.item.id );
			jQuery("#bhaa_firstname").val( ui.item.firstname );
			jQuery("#bhaa_lastname").val( ui.item.lastname );
			jQuery("#bhaa_dateofbirth").val( ui.item.dob );
			jQuery("#bhaa_company").val( ui.item.companyname );
			jQuery("#bhaa_standard").val( ui.item.standard );
			if(ui.item.gender=="M") {
				jQuery("#bhaa_gender-M").prop("checked",true);
			} else {
				jQuery("#bhaa_gender-W").prop("checked",true);
			}
			return false;	
		}
	}).data("ui-autocomplete")._renderItem = function(ul,item){
		return jQuery("<li></li>")
	   	.append("<a>"+item.label+" "+item.id+"</a><small>DOB:"+item.dob+", Status:"+item.status+", Company:"+item.companyname+"</small>")
	    	.data("ui-autocomplete-item", item)
			.appendTo(ul);
	};
	/**
	 * http://jqueryui.com/autocomplete/#custom-data
	 * http://stackoverflow.com/questions/14461787/jqueryui-1-10-0-autocompleter-renderitem-problems
	 * http://jsfiddle.net/XsskB/1/
	 */
	//http://jqueryui.com/autocomplete/#custom-data
});