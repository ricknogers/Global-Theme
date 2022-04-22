<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content, .container, and .row div for the main
 * content
 *
 * @package SNF GROUP
 */
?>
            </div><!-- #content -->
            <section class="footerScroll row">
                <div class="col scroll-to-top top-button-visible stop-scroll">
                    <a id="back-to-top" class="" href="#" title="top">Top</a>
                </div>
                <?php if(!is_page('contact')):?>
                  <!--  <div class="col email-fixed-contact top-button-visible stop-scroll">
                        <div class="sidebar-contact">
                            <div class="form-toggle"></div>
                                <div class="col formTitle">
                                     <h2>Contact Us</h2>
                                </div>
                            <div class="scroll"></div>
                            <div class="popout-form-container">
                                <?php dynamic_sidebar('sidebar-contact-form');?>
                            </div>
                        </div
                    </div>sidebar-contact-->

                <?php else:?>
                <?php endif;?>
            </section>


            <footer id="footer" class="site-footer d-flex justify-content-center" role="contentinfo">
                <div class="container footerStopScroll">
                    <div class="row">
                        <div class="col-sm-12 footer-social-row">
                            <ul class="list-group list-group-horizontal">
	                            <?php $esg_pdf_link = get_option('snf_esg_update_link');?>
	                            <?php $esg_title = get_option('snf_esg_link_title');?>
	                            <?php if ( $esg_pdf_link ) : ?>
                                    <li class="list-group-item  bg-transparent">
                                        <a target="_blank" href="<?php echo $esg_pdf_link;?>"> Environmental & Social Responsibility Report</a>
                                    </li>
	                            <?php endif; ?>
                                <li class="list-group-item  bg-transparent">
	                                <?php  $code_conduct = get_option('snf_code_of_conduct') ;?>
                                    <a target="_blank" href="<?php echo $code_conduct;?>"> Code of Conduct</a>

                                </li>

                            </ul>
                        </div>
                        <div class="col-sm-12 footer-top-row">
                            <?php if ( is_active_sidebar( 'bottom-footer-widget' ) ) :
                                dynamic_sidebar( 'bottom-footer-widget' );
                            else:
                            endif; ?>
                        </div>
                        <div class="col-sm-12 copyRights">
                            <ul>
                                <li>
                                    <p> &copy; <?php echo get_option('company_name') ;?> 2014-<?php echo date('Y'); ?> | A Member of SNF Group | All Rights Reserved.</p>
                                </li>
                            </ul>
                        </div>
                    </div><!--/row-->
                    <div class="blended-logo">
                        <img src="<?php bloginfo('template_directory'); ?>/resources/images/logos/SNF-White.png" alt="SNF Logo" class="img-fluid mx-auto d-block">
                    </div>
                </div><!--/container-->
            </footer><!-- #colophon -->
            <?php if(is_front_page()):?>
            <script  src=https://app.termly.io/embed.min.js data-auto-block="on" data-website-uuid="b8dae01b-f7ee-446f-abc2-8547bb006b4d"></script>
            <?php endif;?>
        <?php wp_footer(); ?>

        <?php if(is_post_type_archive('timeline')):?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/js/swiper.min.js"></script>
        <?php endif;?>
        <?php if(is_page('contact')):?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.5/TweenMax.min.js"></script>
        <?php endif;?>

<script>
    (function($, window){


        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        });

    })(jQuery, window);
</script>

</body>
</html>

