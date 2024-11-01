			function trelsb_handle_links(event, link) {
				// prevent link from propogating
				event.preventDefault();
				
				// set variables
				var linkClicked = link;
				var linkHost = link.host.replace('www.', ''); /* linkHost is the base domain name of the link that was clicked */
				var siteHost = window.location.host.replace('www.', ''); /* siteHost is the base domain name of the WordPress site that the plug-in is running on */
				var externalLink = jQuery(link).attr('href'); /* externalLink is the href value of the link that was clicked */
				/* domainExceptions is an array created from the comma separated value entered in the Omitted Domains field of the plug-in. */
				var domainExceptions = trelsb_php_vars.domainExceptions.toLowerCase().replace(/\s/g, '').replace('www.', '').replace('http://', '').replace('https://', '').split(',');
				/* linkExceptions is an array created from the comma separated value entered in the Omitted Links field of the plug-in. */
				var linkExceptions = trelsb_php_vars.linkExceptions.replace(/\s/g, '').split(',');
				var keycodeEnter = 13;
				var keycodeEsc = 27;
				var keycodeTab = 9;
				var keycodeSpace = 32;
				var linkClickedTarget = linkClicked.getAttribute( "target" );
				
				console.log(linkClicked);
				console.log(linkHost);
				console.log(siteHost);
				console.log(externalLink);
				
				// set overflow hidden on body to keep page from scrolling while modal is open
				jQuery('body').addClass('trelsb-modal-open');
				
				// checks each item in array when doing conditional checks in if statement for popup.
				Array.prototype.contains = function ( linkHost ) {
				   for (i in this) {
				       if (this[i] == linkHost) return true;
				   }
				   return false;
				}		
				
				// Conditional check on link clicked target
				if( linkClickedTarget) {
					var linkHasTarget = 'target="' + linkClickedTarget + '"';
					var blankTarget = '_blank';
				}		
				
				// Conditional check for speed bump modal
				if( linkHost !== siteHost && linkHost !== '' && !domainExceptions.contains( linkHost ) && !linkExceptions.contains( externalLink )) {
					
					event.preventDefault();
					event.stopPropagation();
					
					var modalContent = 
					'<div id="trelsb-external-link-modal" class="trelsb-modal" tabindex="-1" role="dialog" aria-labelledby="trelsb-external-link-modal-message">' + 
						'<div id="trelsb-external-link-modal-message">' + 
							trelsb_php_vars.speedbumpText + 
						'</div>' +
						'<a class="trelsb-button trelsb-external-link-modal-continue"' + linkHasTarget + '" tabindex="1" href="' + externalLink + '" aria-label="Continue">' + 
							trelsb_php_vars.continueText + 
						'</a>' + 
						'<span class="trelsb-button trelsb-external-link-modal-close" tabindex="2" aria-label="Close">' + 
							trelsb_php_vars.cancelText + 
						'</span>' +
					'</div><!-- #trelsb-external-link-modal -->';
					
					jQuery('body').append('<div class="trelsb-modal-overlay">' + modalContent + '</div><!-- .trelsb-modal-overlay -->');
					
					jQuery('.trelsb-external-link-modal-continue').focus();
					
					var contInput = jQuery('.trelsb-external-link-modal-continue');
					var closeInput = jQuery('.trelsb-external-link-modal-close');
					
					contInput.click(function() {
						jQuery('body').removeClass('trelsb-modal-open');
						jQuery('.trelsb-modal-overlay').remove();
					});
					
					closeInput.click(function() {
						jQuery('.trelsb-modal-overlay').remove();
						jQuery('body').removeClass('trelsb-modal-open');
						linkClicked.focus();
					});
					
					
					// Keyboard Navigation Helpers
					/* redirect last tab to first input */
					closeInput.keydown(function(event) {
						if((event.which === keycodeTab && !event.shiftKey)) {
							event.preventDefault();
							contInput.focus();	
						}
						if( event.keyCode == keycodeSpace || event.keyCode == keycodeEnter ){
							event.preventDefault();
					        closeInput.click();
					    }
					});
					
					/* redirect first shift+tab to last input */
					contInput.keydown(function(event) {
						if((event.which === keycodeTab && event.shiftKey)) {
							event.preventDefault();
							closeInput.focus();
						}
						if( event.keyCode == keycodeSpace || event.keyCode == keycodeEnter ){
							event.preventDefault();
							contInput.click();
							jQuery('body').removeClass('trelsb-modal-open');
							jQuery('.trelsb-modal-overlay').remove();
							if( blankTarget ) {
						window.open(externalLink, linkClickedTarget);
					} else {
						location.href = externalLink;
					}
						}
					});
					
					/* Close modal on press of escape key */
					jQuery(document).keyup(function(event) {
						if (event.keyCode == keycodeEsc) closeInput.click();
						jQuery('body').removeClass('trelsb-modal-open');				
					});
				} else {
					// Propogate original link, link or domain name was found in plug-in settings Omitted Domains or Omitted Links
					jQuery('body').removeClass('trelsb-modal-open');
					if( blankTarget ) {
						window.open(externalLink, linkClickedTarget);
					} else {
						location.href = externalLink;
					}
				}
			}

( function() {
	jQuery( document ).ready(function() {

			// check if any links were generated by gallery-custom-links plugin https://wordpress.org/plugins/gallery-custom-links/ and add back in the event handle if removed
			jQuery('a').each(function(link){
				if (this.classList.contains('custom-link')) {
					this.addEventListener('click', function(event){
						event.stopImmediatePropagation();
						trelsb_handle_links(event, this)
						return false;
					})
				}
			})

			// on click of a link, toss it's info at the trelsb_handle_links function to decide whether it's external
			jQuery('a').click(function(event) {
				trelsb_handle_links(event, this)
			});

			// on click of a link determine if it is external or not and if it is pop up an overlay with speed bump modal.
		// Track document clicks when modal is open and if not modal close modal and remove body class
		jQuery(document).click(function (event) {
			if( jQuery('body').hasClass('trelsb-modal-open')) {
			   if(!jQuery(event.target).closest('.trelsb-modal').length && !jQuery(event.target).is('.trelsb-modal')) {
			     jQuery('.trelsb-modal-overlay').remove();
				 jQuery('body').removeClass('trelsb-modal-open');
			   }   
		   }  
		});
		});
} )();
