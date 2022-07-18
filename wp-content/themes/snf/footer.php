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
<footer class="section bg-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-12">
                <div class="">
                    <h6 class="footer-heading text-uppercase text-white">Company</h6>
                    <ul class="list-inline list-unstyled footer-link mt-2">
	                    <?php $esg_pdf_link = get_option('snf_esg_update_link');?>
	                    <?php $esg_title = get_option('snf_esg_link_title');?>
	                    <?php if ( $esg_pdf_link ) : ?>
                            <li>
                                <a target="_blank" href="<?php echo $esg_pdf_link;?>"> Environmental & Social Responsibility Report</a>
                            </li>
	                    <?php endif; ?>
                        <li>
		                    <?php  $code_conduct = get_option('snf_code_of_conduct') ;?>
                            <a target="_blank" href="<?php echo $code_conduct;?>"> Code of Conduct</a>
                        </li>
                        <li>
                            <a href="<?php echo home_url('/');?>sustainability/overview/">Sustainability</a>
                        </li>
                        <li>
                            <a href="<?php echo home_url('/');?>sustainability/csr/">Corporate Social Responsibility</a>
                        </li>
                        <li>
                            <a href="<?php echo home_url('/');?>investors">Investor Center</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12">
                <div class="">
                    <h6 class="footer-heading text-uppercase text-white">Stay Updated</h6>
                    <ul class="list-inline list-unstyled footer-link mt-2">
                        <li><a href="<?php echo home_url('/');?>about-us/">About Us </a></li>
                        <li><a href="https://www.linkedin.com/company/snf-group/" target="_blank">Linkedin</a></li>
                        <li><a href="<?php echo home_url('/') ;?>contact/">Corporate Contact</a></li>
                        <li><a href="<?php echo home_url('/') ;?>news/">News</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12">
                <div class="">
                    <h6 class="footer-heading text-uppercase text-white">Markets</h6>
                    <ul class="list-inline list-unstyled footer-link mt-2">
                        <li><a href="<?php echo home_url('/');?>industry/agriculture">Agriculture </a></li>
                        <li><a href="<?php echo home_url('/');?>industry/construction">Civil Engineering</a></li>
                        <li><a href="<?php echo home_url('/');?>industry/dredging">Dredging</a></li>
                        <li><a href="<?php echo home_url('/');?>industry/equipment">Equipment & Engineering</a></li>
                        <li><a href="<?php echo home_url('/');?>industry/homecare-ii/">Home Industrial & Institutional </a></li>
                        <li><a href="<?php echo home_url('/');?>industry/industrial-water-treatment/">Industrial Water Treatment</a></li>
                        <li><a href="<?php echo home_url('/');?>industry/mining">Mining</a></li>
                        <li><a href="<?php echo home_url('/');?>industry/municipal-water-treatment/">Municipal Water Treatment</a></li>
                        <li><a href="<?php echo home_url('/');?>industry/oil-gas">Oil & Gas</a></li>
                        <li><a href="<?php echo home_url('/');?>industry/personal-care">Personal Care</a></li>
                        <li><a href="<?php echo home_url('/');?>industry/pulp-paper/">Pulp & Paper</a></li>
                        <li><a href="<?php echo home_url('/');?>industry/textiles">Textiles</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-sm-12">
                <div class="footer-link">
                    <h6 class="footer-heading text-uppercase text-white">Contact Us</h6>
                    <p class="contact-info"><a href="tel:+33 (0) 477 36 86 00">+33 (0) 477 36 86 00 </a> (SNF Group HQ)</p>
                    <p class="contact-info"><a href="mailto:info@snf.com">info@snf.com</a></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 md-text-center mt-md-1  footer_legal_links">
                <ul class="list-group list-group-horizontal-md footer-link p-sm-0">
                    <li class="list-group-item bg-transparent border-0"><a href="<?php echo home_url('/');?>privacy-policy/">Privacy Policy </a></li>
                    <li class="list-group-item bg-transparent border-0"><a href="<?php echo home_url('/');?>cookies-policy/">Cookies Policy</a></li>
                    <li class="list-group-item bg-transparent border-0"><a href="<?php echo home_url('/') ;?>do-not-sell-my-personal-information/">Do Not Sell My Personal Information</a></li>
                    <li class="list-group-item bg-transparent border-0"><a href="<?php echo home_url('/') ;?>terms-of-use/">Terms of Use</a></li>
                    <li class="list-group-item bg-transparent border-0"><a href="<?php echo home_url('/') ;?>contact/">Corporate Contact</a></li>
                    <li class="list-group-item bg-transparent border-0"><a href="<?php echo home_url('/') ;?>sitemap_index.xml/">Site Map</a></li>
                </ul>
            </div>
            <div class="col-sm-12 text-center  border-top copyright ">
                <p class="footer-alt mb-3 mt-3 f-14">&copy; <?php echo get_option('company_name') ;?> 2014-<?php echo date('Y'); ?> | A Member of SNF Group | All Rights Reserved.</p>
            </div>
        </div>
    </div>
    <div class="blended-logo">
        <img src="<?php bloginfo('template_directory'); ?>/resources/images/logos/SNF-White.png" alt="SNF Logo" class="img-fluid mx-auto d-block" lazyload="load">
    </div>
</footer>



<?php wp_footer(); ?>


<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "6l7qj68oee");
</script>

<script>
    (function($, window){
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox();
        })

    })(jQuery, window);
</script>

</body>
</html>