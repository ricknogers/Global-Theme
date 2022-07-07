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

            if ($(window).scrollTop() > 45) {

                if (window.innerWidth < 768) {
                    $('.nav-container#mobileNavigation').addClass('sticky-top');
                }else{
                    $('.nav-container#desktopNavigation').addClass('sticky-top');
                }
            }else{



            }





        });
        
    });
</script>
    <?php
}