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

(function($, window){
    $('#markets_carousel').on('slide.bs.carousel', function (e) {


        var $e = $(e.relatedTarget);
        var idx = $e.index();
        var itemsPerSlide = 3;
        var totalItems = $('.carousel-item').length;

        if (idx >= totalItems-(itemsPerSlide-1)) {
            var it = itemsPerSlide - (totalItems - idx);
            for (var i=0; i<it; i++) {
                // append slides to end
                if (e.direction=="left") {
                    $('.carousel-item').eq(i).appendTo('.carousel-inner');
                }
                else {
                    $('.carousel-item').eq(0).appendTo('.carousel-inner');
                }
            }
        }
    });



})(jQuery, window);