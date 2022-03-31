(function($) {
    'use strict'
    const ajax_url = prevent_direct_access_gold_setting_data.ajax_url;
    let siteActivated;
    let activateAllSite;
    let myInterval;
    function getActivated() {
        $.ajax({
            url: ajax_url,
            type: 'GET',
            data: {
                action: 'pda_gold_activated_statistics',
            },
            success: function (data) {
                if (data.hasOwnProperty('status')) {
                    siteActivated.text(data.num);
                    if (!data.status) {
                        $('#license-loading').remove();
                        activateAllSite.attr("type", "submit");
                        clearInterval(myInterval);
                        toastr.success('License activated successfully on existing sites', 'Prevent Direct Access Gold 3.0')
                    }
                }
            },
            error: function (error) {
                console.log("Errors", error);
                $('#license-loading').remove();
                activateAllSite.attr("type", "submit");
                clearInterval(myInterval);
                toastr.error('Fail to activate PDA Gold license on existing sites. Please try again.', 'Prevent Direct Access Gold 3.0')
            },
            timeout: 1000
        });
    }
    $(document).ready(function() {
        if ( (location.search).substr(1).includes('license') ) {
            $.ajax({
                url: ajax_url,
                type: 'GET',
                data: {
                    action: 'pda_gold_activated_statistics',
                },
                timeout: 1000
            }).done(data => {
                activateAllSite = $("#activate-all-sites");
                if ( data.status ){
                    let infoSiteActivated = $('#info-site-activated');
                    if (infoSiteActivated.hasClass('pda-display-none')) {
                        infoSiteActivated.removeClass('pda-display-none');
                    }
                    let activateAllSite = $("#activate-all-sites");
                    activateAllSite.attr("type", "hidden");
                    $('#span-activate').append('<div id="license-loading" class="lds-ring"><div></div><div></div><div></div><div></div></div>');
                    siteActivated = $('#site-activated');
                    myInterval = setInterval(getActivated, 1000);
                }
            }).error(err => console.log(err));
        }
    });

})(jQuery);