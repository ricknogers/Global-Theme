(function($, window){
    $(document).ready(function(){
        /*=================Investors Tool Tip Next to Investor Category ======================*/
        $('[data-toggle="popover"]').popover();
    });

    $(document).ready(function () {



    });

    // JS Dialog Box Pop-Up for Locations, Products, TopBar Global Link, and markets
    $(document).ready(function(){
        jQuery.noConflict();
        var elems = document.getElementsByClassName('confirmation');
        var siteTitle = $("meta[property='og:site_name']").attr("content");

        var confirmIt = function (e) {
            if (!confirm(' You are now leaving the ' +  siteTitle +  ' website. The following pages are developed and managed by The SNF Group, the global parent company of '  +  siteTitle + ' Products, services, and information provided by The SNF Group may not be relevant or available for ' +  siteTitle + '. Please contact your regional subsidiary for specific availability.')) e.preventDefault();
        };
        for (var i = 0, l = elems.length; i < l; i++) {
            elems[i].addEventListener('click', confirmIt, false);
        }
    });


})(jQuery, window);

