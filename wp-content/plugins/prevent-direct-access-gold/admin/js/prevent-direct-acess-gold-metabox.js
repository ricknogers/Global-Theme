(function( $ ) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     $(function() {

	 });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */
    $(function() {
        var restApiRoot = pda_gold_v3_metabox.api_url.replace(/\/$/, '');
        $("#pda_loader").hide();
        $("#pda_v3_protection_toggle").change(function () {
            if ( $("#pda_v3_protection_toggle").prop('checked') === true ) {
                $(".pda_v3_wrap_file_access_permission").show();
            } else {
                $(".pda_v3_wrap_file_access_permission").hide();
            }
        });
        $("#pda_v3_protection_toggle").trigger("change");

        $("#pda_file_access_permission_value").change(function() {
            var _data = {
                action: 'pda_update_file_access_permission',
                select_role: $("#pda_file_access_permission_value").val(),
                attachment_id: $("#pda_v3_post_id").val(),
            }
            $('#pda_file_access_permission_value').attr("disabled", true);
            $("#pda_loader").show();
            $.ajax({
                url: ip_block_server_data.ajaxurl,
                type: 'POST',
                data: _data,
                success: function (data) {
                    if (data) {
                        $('#pda_file_access_permission_value').attr("disabled", false);
                        $("#pda_loader").hide();
                        console.log("Success", data);
                    } else {
                        console.log("Failed", data);
                    }
                },
                error: function (error) {
                    console.log("Errors", error);
                },
                timeout: 5000
            });
        });

        $(".pdav3-on").click(function(evt) {
            // evt.preventDefault();
            var id = $("#pda_v3_post_id").val();
            $.ajax({
                url: restApiRoot + '/pda/v3/files/' + id,
                type: 'POST',
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0',
                    'X-WP-Nonce': pda_gold_v3_metabox.nonce
                },
                success: function(data) {
                    console.log("Ok", data);
                    if ( data.url ) {
                        $('#attachment_url').val(data.url);
                    }
                    $("#pda_v3_protection_toggle").attr('checked', true);
                },
                error: function(error) {
                    console.error("Opps", error);
                }
            });
            return;
        })

        $(".pdav3-off").click(function(evt) {
            // evt.preventDefault();
            var id = $("#pda_v3_post_id").val();
            $.ajax({
                url: restApiRoot + '/pda/v3/un-protect-files/' + id,
                type: 'POST',
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0',
                    'X-WP-Nonce': pda_gold_v3_metabox.nonce
                },
                success: function(data) {
                    console.log("OK", data);
                    if ( data.url ) {
                        $('#attachment_url').val(data.url);
                    }
                    $("#pda_v3_protection_toggle").attr('checked', false);
                },
                error: function(error) {
                    console.error("Opps", error);
                }
            });
            return;
        })
    });

})( jQuery );
