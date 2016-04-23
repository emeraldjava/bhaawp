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

			bootstrap_alert('#form_errors', 'User must dismiss this message manually');
			return false;
		}
	})
	.data("ui-autocomplete")._renderItem = function( ul, item) {
		// http://stackoverflow.com/questions/14442471/how-to-set-up-jquery-ui-1-10-autocomplete-custom-display
		return jQuery("<li>")
			.data("ui-autocomplete-item", item)
	   		.append("<a>"+item.label+" "+item.id+"</a><small>DOB:"+item.dob+", Status:"+item.status+", Company:"+item.companyname+"</small>")
			.appendTo(ul);
	};

	jQuery('#formSubmitButton').click(function() {
		/* when the button in the form, display the entered values in the modal */
		jQuery('#lname').text(jQuery('#bhaa_lastname').val());
		jQuery('#fname').text(jQuery('#bhaa_firstname').val());
	});

	jQuery('#modalsubmit').click(function() {
		/* when the submit button in the modal is clicked, submit the form */
		//alert('submitting');
		jQuery('#registerform').submit();
	});

    // function bootstrap_alert(elem, message, timeout) {
  	//   jQuery(elem).show().html('<div class="alert"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><span>'+message+'</span></div>');
		//
  	//   if (timeout || timeout === 0) {
  	//     setTimeout(function() {
  	//     	jQuery(elem).alert('close');
  	//     }, timeout);
  	//   }
  	// };
	/**
	 * http://jqueryui.com/autocomplete/#custom-data
	 * http://stackoverflow.com/questions/14461787/jqueryui-1-10-0-autocompleter-renderitem-problems
	 * http://jsfiddle.net/XsskB/1/
	 */
	//http://jqueryui.com/autocomplete/#custom-data
});
