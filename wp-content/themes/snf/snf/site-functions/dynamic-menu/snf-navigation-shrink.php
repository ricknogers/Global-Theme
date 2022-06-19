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
	            $('.transition-navbar').addClass('sticky-top shrink');
//                   $('.navigation-block').addClass('sticky-top shrink');
                oldLogo.css("display", "none");
                newLogo.css("display", "block");
                newLogoImg.css("display", "block");
            }
            else{
                $('.transition-navbar').removeClass('sticky-top shrink');
//                   $('.navigation-block').removeClass('sticky-top shrink');
                newLogo.css("display", "none");
                oldLogo.css("display", "block");
            }
        });
    });
</script>
    <?php
}