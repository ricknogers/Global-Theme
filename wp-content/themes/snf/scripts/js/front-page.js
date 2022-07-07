(function($, window){
    // SNF Overview Video Modal
    $(document).ready(function() {
        $('#play').on('click', function (e) {
            e.preventDefault();
            $("#player")[0].src += "?autoplay=1";
            $('#player').show();
            $('#video-cover').hide();
        })
        $('#overviewVideo').on('hidden.bs.modal', function (e) {
            $('#overviewVideo iframe').attr("src", $("#overviewVideo iframe").attr("src"));
        });

    });
    $(document).ready(function() {
        $('#play-inv').on('click', function (e) {
            e.preventDefault();
            $("#player")[0].src += "?autoplay=1";
            $('#player').show();
            $('#video-cover').hide();
        })
        $('#investorOverviewVideo').on('hidden.bs.modal', function (e) {
            $('#investorOverviewVideo iframe').attr("src", $("#investorOverviewVideo iframe").attr("src"));
        });

    });

})(jQuery, window);
(function($, window){
    $('#news_carousel').on('slide.bs.carousel', function (e) {
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