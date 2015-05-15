jQuery(document).ready(function() {
    
    // settings popup
    jQuery('a.settings-popup').click(function(event) {
        
        jQuery.colorbox({
			href: jQuery(this).attr('href'),
			escKey: false,
			scrolling: false,
			overlayClose: false,
			innerWidth: 440,
			onComplete: function() {
			    
			    var savingInProgress = false;
			    var errorsContainerEl = jQuery('#popup-settings-errors');
			    
			    jQuery('#popup-settings-cancel, #popup-settings-close').click(function(event) {
                    if (!savingInProgress) {
                        jQuery.colorbox.close();
                    }
					event.preventDefault();
				});
				
				jQuery('#popup-settings-yes').click(function(event) {
				    savingInProgress = true;
				    jQuery('#popup-settings-cancel, #popup-settings-yes').attr('disabled', 'disabled');
				    errorsContainerEl.hide().empty();
				    
				    new Ajax.Request(saveSettingsUrl, {
                        method: 'post',
                        parameters: {
                            'id': presetId, // variable defined in template
                            'store_id': jQuery('#popup-settings-store-id').val(),
                            'package': jQuery('#popup-settings-package').val(),
                            'locale': jQuery('#popup-settings-locale').val(),
                            'template': jQuery('#popup-settings-template').val(),
                            'skin': jQuery('#popup-settings-skin').val(),
                            'layout': jQuery('#popup-settings-layout').val(),
                            'default': jQuery('#popup-settings-default').val()
                        },
                        onComplete:function (transport) {
                            
                            var responseHash = jQuery.evalJSON(transport.responseText);
    					    // there was some errors, show them to user
    					    if (Object.keys(responseHash.errors).length) {
    					        savingInProgress = false;
    					        
    					        jQuery.each(responseHash.errors, function(errorType, errorDescr) {
    					            errorsContainerEl.append('<p>' + errorDescr + '</p>');
    					        });
    					        errorsContainerEl.show();
    					        jQuery('#popup-settings-cancel, #popup-settings-yes').removeAttr('disabled');
    					        
    					        jQuery.colorbox.resize(); // resize popup, so that error messages would fit
    					        
    					    } else {
    					        document.location.href = refreshGridUrl;
    					    }
                        }
                    });
				});
				
				jQuery('#popup-settings-form').submit(function(event) {
				    jQuery('#popup-settings-yes').trigger('click');
				    event.preventDefault();
				});
			}
        });
        
        event.preventDefault();
    });
    
    
    // delete confirmation popup
    jQuery('a.delete-popup').click(function(event) {
        var popupId = '#delete-popup';
        var self = this;
        
        jQuery.colorbox({
			href: popupId,
			inline: true,
			escKey: false,
			scrolling: false,
			overlayClose: false,
			innerWidth: 440,
			onLoad: function() {
			    var rawTitle = jQuery(self).attr('title');
			    var escapedTitle = jQuery('<div/>').text(rawTitle).html();
			    jQuery('#delete-popup-tune-name').empty().append('"' + escapedTitle + '"');
			    
			    jQuery(popupId).show();
			    
			}, onComplete: function() {
			    
			    var deleteInProgress = false;
			    
			    jQuery('#popup-delete-cancel, #popup-delete-close').click(function(event) {
			        if (!deleteInProgress) {
                        jQuery.colorbox.close();
			        }
					event.preventDefault();
				});
				
				jQuery('#popup-delete-yes').click(function(event) {
				    deleteInProgress = true;
				    
				    jQuery('#popup-delete-cancel, #popup-delete-yes').attr('disabled', 'disabled');
				    document.location.href = jQuery(self).attr('href');
				    event.preventDefault();
				});
				
			}, onCleanup: function() {
			    jQuery(popupId).hide();
			}
        });
        
        event.preventDefault();
    });
});