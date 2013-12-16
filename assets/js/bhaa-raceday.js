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
			jQuery("#runner").val( ui.item.id );
			jQuery("#firstname").val( ui.item.firstname );
			jQuery("#lastname").val( ui.item.lastname );
			jQuery("#dateofbirth").val( ui.item.dob );
			jQuery("#company").val( ui.item.companyname );
			jQuery("#standard").val( ui.item.standard );
			if(ui.item.gender=="M") {
				jQuery("#gendermale").prop("checked",true);
			} else {
				jQuery("#genderfemale").prop("checked",true);
			}
			return true;	
		}
	}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		return jQuery("<li></li>")
	    	.data("item.autocomplete", item)
	    	.append("<a>"+item.label+" "+item.id+"</a><small>DOB:"+item.dob+", Status:"+item.status+", Company:"+item.companyname+"</small>")
			.appendTo(ul);
	};
});