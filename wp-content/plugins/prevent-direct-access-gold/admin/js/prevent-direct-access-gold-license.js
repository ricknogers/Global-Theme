(function($) {
    'use strict';

    $(function () {
        $('#pda_license_form').submit(function (evt) {
            evt.preventDefault();
            var license = $('#prevent-direct-access-gold_license_key').val();
            if(!license) {
                $("#prevent-direct-access-gold_l_error").text("This field is required!")
            } else {
                $("#prevent-direct-access-gold_l_error").text("");
                _checkLicense(license, function (error) {
                    if(error) {
                        console.log(error);
                    } else {
                        location.reload();
                    }
                });
            }

        });
    });

    function _checkLicense(license, cb) {
        var _data = {
            action: 'Prevent_Direct_Access_Gold_Check_Licensed',
            license: license.trim(),
			product_id: $("#product_id").val(),
            security_check: $("#prevent-direct-access-gold_nonce").val(),
        }
        $('#submit').val('Submitting');
        $("#submit").prop("disabled", true);
        $.ajax({
            url: prevent_direct_access_gold_license_data.ajax_url,
            type: 'POST',
            data: _data,
            success: function (res) {
                $('#submit').val('Save Changes');
                $("#submit").prop("disabled", false);
                //Do something with the result from server
                if (res == 'invalid_nonce') {
                    alert('No! No! No! Verify Nonce Fails!');
                } else if(res) {
                    console.log("Res", res);
                    //success here
                    if(res.data.errorMessage || res.data == false) {
                        toastr.error(res.data.errorMessage, 'Prevent Direct Access Gold');
                        cb(res.data);
                    } else {
                        console.log("Success", res);
                        toastr.success('Your settings have been updated successfully!', 'Prevent Direct Access Gold');
                        cb();
                    }

                } else {
                    console.log("Failed", res);
                    cb(res);
                }
            },
            error: function (error) {
                $("#submit").prop("disabled", false);
                cb(error);
            }

        })
    }
})( jQuery );
