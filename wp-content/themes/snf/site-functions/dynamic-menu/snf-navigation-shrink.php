<?php
/**
 * Make Header Shrink on Page Scroll
 **/
add_action ('wp_footer','vr_shrink_head',1);
function vr_shrink_head() {
?>
<script>
    jQuery(document).ready(function($) {
        $(window).scroll(function () {
            var oldLogo = $(".default-logo");
            var newLogo = $(".scroll-logo ");
            var newLogoImg =$(".default-img");
            if ($(window).scrollTop() > 45) {
	            
	            $('#mobileNavigation').addClass('fixed-top');
//                   $('.navigation-block').addClass('sticky-top shrink');

            }
            else{
	            $('#desktopNavigation').removeClass('fixed-top');
                $('#mobileNavigation').removeClass('fixed-top');
//                   $('.navigation-block').removeClass('sticky-top shrink');
                newLogo.css("display", "none");
                oldLogo.css("display", "block");
            }
        });
        
    });
</script>
    <?php
}