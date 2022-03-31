(function (window, $) {
    var regex = /^((?!0)(?!.*\.$)((1?\d?\d|25[0-5]|2[0-4]\d|\*)(\.|$)){4})|(([0-9a-f]|:){1,4}(:([0-9a-f]{0,4})*){1,7})$/;
    $(document).ready(function($){
        $('#pda_gold_ip_block').tagsInput({
            defaultText: '',
            delimiter: ';',
            width: '765px',
            pattern: regex,
        });

        var form = $("#pda_gold_ip_block_form");
        form.submit(function (e) {
            e.preventDefault();
            var ips = $("#pda_gold_ip_block").val();
            updateSettings({
                pda_gold_ip_block: ips
            }, function (error) {
                if(error) {
                    console.error(error);
                }
                // location.reload(true);
            });
        });

        function updateSettings(settings, cb) {
            var _data = {
                action: 'update_ip_block',
                settings: settings,
                security_check: $("#nonce_ip_block").val()
            }
            $("#submit").val("Submitting");
            $("#submit").prop("disabled", true);
            $.ajax({
                url: ip_block_server_data.ajaxurl,
                type: 'POST',
                data: _data,
                success: function(data) {
                    $("#submit").prop("disabled", false);
                    if (data == 'invalid_nonce') {
                        alert('No! No! No! Verify Nonce Fails!');
                    } else if(data) {
                        toastr.success('Your settings have been updated successfully!');
                    } else {
                        console.log("Failed", data);
                    }
                    cb();
                },
                error: function(error) {
                    $("#submit").prop("disabled", false);
                    console.log("Errors", error);
                    cb(error);
                },
                timeout: 5000
            });
        }
    });
})(window, jQuery.noConflict());