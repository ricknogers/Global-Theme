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

    //scroll slides on swipe for touch enabled devices
    $("#myCarousel").on("touchstart", function(event){
        var yClick = event.originalEvent.touches[0].pageY;
        $(this).one("touchmove", function(event){
            var yMove = event.originalEvent.touches[0].pageY;
            if( Math.floor(yClick - yMove) > 1 ){
                $(".carousel").carousel('next');
            }
            else if( Math.floor(yClick - yMove) < -1 ){
                $(".carousel").carousel('prev');
            }
        });
        $(".carousel").on("touchend", function(){
            $(this).off("touchmove");
        });
    });
        //scroll slides on swipe for touch enabled devices
        $("#flexible-slider").on("touchstart", function(event){
            var yClick = event.originalEvent.touches[0].pageY;
            $(this).one("touchmove", function(event){
                var yMove = event.originalEvent.touches[0].pageY;
                if( Math.floor(yClick - yMove) > 1 ){
                    $(".carousel").carousel('next');
                }
                else if( Math.floor(yClick - yMove) < -1 ){
                    $(".carousel").carousel('prev');
                }
            });
            $(".carousel").on("touchend", function(){
                $(this).off("touchmove");
            });
        });

    });
})(jQuery, window);