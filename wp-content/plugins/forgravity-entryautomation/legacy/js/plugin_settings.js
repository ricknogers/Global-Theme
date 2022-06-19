var FGEntryAutomationSettings = function() {

	var self = this,
		$    = jQuery;

	self.init = function() {

		// Add localized strings.
		self.strings = forgravity_entryautomation_plugin_settings_strings;

		// Initialize Extension Actions.
		self.runExtensionAction();

	}





	// # EXTENSIONS ----------------------------------------------------------------------------------------------------

	/**
	 * Handle Run Task Now button.
	 *
	 * @since 1.3
	 */
	self.runExtensionAction = function() {

		$( document ).on( 'click', '#gaddon-setting-row-extensions a[data-action]', function( e ) {

			var $button = $( this ),
				action = $button.data( 'action' ),
				plugin = $button.data( 'plugin' );

			// If this is the upgrade action, return.
			if ( 'upgrade' === action ) {
				return true;
			}

			e.preventDefault();

			// Disable button.
			$button.attr( 'disabled', 'disabled' );

			// Change button text.
			$button.html( self.strings.processing[ action ] );

			// Prepare request data.
			var data = {
				action:    'fg_entryautomation_extension_action',
				extension: {
					action: action,
					plugin: plugin
				},
				nonce:     self.strings.nonce
			};

			// Run task.
			$.ajax(
				{
					url:      ajaxurl,
					type:     'POST',
					dataType: 'json',
					data:     data,
					success:  function( response ) {

						// If could not process action, display error message.
						if ( ! response.success ) {
							alert( response.data.error );
						}

						// Update button.
						$button.data( 'action', response.data.newAction );
						$button.html( response.data.newText );

						// Enable button.
						$button.removeAttr( 'disabled' );

					}

				}

			)

		} );

	}

	self.init();

}

jQuery( document ).ready( function() { new FGEntryAutomationSettings(); } );
