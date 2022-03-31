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
                            <?php if ( is_active_sidebar( 'social-widget' ) ) :
                                dynamic_sidebar( 'social-widget' );
                            else:
                            endif; ?>
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
        <?php wp_footer(); ?>
        <?php if(is_page_template('market-sites/market-home.php')):?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.8.2/js/lightbox.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.2.0/anime.min.js"></script>
<!--            <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>-->


        <?php endif;?>
        <?php if(is_post_type_archive('timeline')):?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/3.4.2/js/swiper.min.js"></script>
        <?php endif;?>
        <?php if(is_page('contact')):?>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.5/TweenMax.min.js"></script>
        <?php endif;?>

</body>
</html>

