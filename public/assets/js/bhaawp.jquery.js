jQuery(document).ready( function() {
	
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
	
	jQuery("#runner_search").autocomplete({
		source: function(req, response){
//			jQuery.getJSON(bhaawp.ajaxurl+'?callback=?action='+acs_action, req, response);
			jQuery.ajax({
				  url: bhaaAjax.ajaxurl,
				  dataType: 'json',
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
	//});
	
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
	
	/* 
	 http://odoepner.wordpress.com/2012/07/11/autocomplete-combobox-using-jquery-ui/
	 http://www.learningjquery.com/2010/06/a-jquery-ui-combobox-under-the-hood/
	 http://jsfiddle.net/69akn/
	 */
	//jQuery( function( jQuery ) {
	jQuery.widget( "custom.combobox", {
	      _create: function() {
	        this.wrapper = jQuery( "<span>" )
	          .addClass( "custom-combobox" )
	          .insertAfter( this.element );
	 
	        this.element.hide();
	        this._createAutocomplete();
	        this._createShowAllButton();
	      },
	 
	      _createAutocomplete: function() {
	        var selected = this.element.children( ":selected" ),
	          value = selected.val() ? selected.text() : "";
	 
	        this.input = jQuery( "<input>" )
	          .appendTo( this.wrapper )
	          .val( value )
	          .attr( "title", "" )
	          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
	          .autocomplete({
	            delay: 0,
	            minLength: 0,
	            source: jQuery.proxy( this, "_source" )
	          });
	          //.tooltip({
	          //  tooltipClass: "ui-state-highlight"
	          //});
	 
	        this._on( this.input, {
	          autocompleteselect: function( event, ui ) {
	            ui.item.option.selected = true;
	            this._trigger( "select", event, {
	              item: ui.item.option
	            });
	          },
	 
	          autocompletechange: "_removeIfInvalid"
	        });
	      },
	 
	      _createShowAllButton: function() {
	        var input = this.input,
	          wasOpen = false;
	 
	        jQuery( "<a>" )
	          .attr( "tabIndex", -1 )
	          .attr( "title", "Show All Items" )
	          .tooltip()
	          .appendTo( this.wrapper )
	          .button({
	            icons: {
	              primary: "ui-icon-triangle-1-s"
	            },
	            text: false
	          })
	          .removeClass( "ui-corner-all" )
	          .addClass( "custom-combobox-toggle ui-corner-right" )
	          .mousedown(function() {
	            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
	          })
	          .click(function() {
	            input.focus();
	 
	            // Close if already visible
	            if ( wasOpen ) {
	              return;
	            }
	 
	            // Pass empty string as value to search for, displaying all results
	            input.autocomplete( "search", "" );
	          });
	      },
	 
	      _source: function( request, response ) {
	        var matcher = new RegExp( jQuery.ui.autocomplete.escapeRegex(request.term), "i" );
	        response( this.element.children( "option" ).map(function() {
	          var text = jQuery( this ).text();
	          if ( this.value && ( !request.term || matcher.test(text) ) )
	            return {
	              label: text,
	              value: text,
	              option: this
	            };
	        }) );
	      },
	 
	      _removeIfInvalid: function( event, ui ) {
	 
	        // Selected an item, nothing to do
	        if ( ui.item ) {
	          return;
	        }
	 
	        // Search for a match (case-insensitive)
	        var value = this.input.val(),
	          valueLowerCase = value.toLowerCase(),
	          valid = false;
	        this.element.children( "option" ).each(function() {
	          if ( jQuery( this ).text().toLowerCase() === valueLowerCase ) {
	            this.selected = valid = true;
	            return false;
	          }
	        });
	 
	        // Found a match, nothing to do
	        if ( valid ) {
	          return;
	        }
	 
	        // Remove invalid value
	        this.input
	          .val( "" )
	          .attr( "title", value + " didn't match any item" )
	          .tooltip( "open" );
	        this.element.val( "" );
	        this._delay(function() {
	          this.input.tooltip( "close" ).attr( "title", "" );
	        }, 2500 );
	        this.input.data( "ui-autocomplete" ).term = "";
	      },
	 
	      _destroy: function() {
	        this.wrapper.remove();
	        this.element.show();
	      }
	    });
	 // });

    jQuery(function() {
    	jQuery("#bhaa_runner_company").combobox();
    	jQuery("#toggle").click(function() {
    		jQuery("#combobox").toggle();
		});
	});
		
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
				jQuery("#gender-M").prop("checked",true);
			} else {
				jQuery("#gender-W").prop("checked",true);
			}
			return false;	
		}
	})
	//.data("ui-autocomplete")._renderItem = function( ul, item ) {
	//	return jQuery("<li>")
	//    	.append("<a>"+item.label+" "+item.id+"</a><small>DOB:"+item.dob+", Status:"+item.status+", Company:"+item.companyname+"</small>")
	//		.appendTo(ul);
	//}
	;
	//http://jqueryui.com/autocomplete/#custom-data

});