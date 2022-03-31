(function($, window){
    $(document).ready(function(){
        function checkPosition() {
            if ($(this).scrollTop() > 200) {
                $('#back-to-top').fadeIn(500);
            } else {
                $('#back-to-top').fadeOut(300);
            }
        }
        // Show or hide the sticky footer button
        $(window).scroll(checkPosition);
        // Animate the scroll to top
        $('#back-to-top').click(function(event) {
            event.preventDefault();

            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
        checkPosition();
    });
})(jQuery, window);