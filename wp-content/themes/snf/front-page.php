<?php
/**
 * The main template file for the Home Page of the site.
 *
 *
 * @package SNF Group
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-sm-12 introduction mb-md-5 mt-md-5">
            <?php the_content();?>
        </div>
    </div>
</div>
<!-- SECTION -->
<div class="oval-overlay executive-highlight">
    <section class="geometric-overlay bg-img" style="background-image:url(https://snf.com/wp-content/uploads/2021/10/Industrial-Waste-Treatment.jpg); ">
        <div class="container p-md-5 p-sm-2 d-flex h-100 ">
            <div class="row align-items-center overview-wrapper">
                <div class="col-md-8 col-sm-12 overview">
                    <div class="clearfix">
                        <div class="col-sm-12 branded-title-seperation">
                            <section class="branded_title_container">
                                <h2 class="display-3 text-white">SNF Corporate Video</h2>
                                <h6 class="text-mute text-white mt-md-3 mb-md-4">Learn more about what SNF does and all the industries we are involved in.</h6>
                            </section><!--branded_title_container-->
                            <div class="snf-link-wrapper ">
                                <div class="snf-link">
                                    <a href="<?php echo home_url('/');?>about-us" class="product-list-link">Learn More</a>
                                </div>
                            </div><!--snf-link-wrapper-->
                        </div><!--branded-title-seperation-->
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 text-center overview video-overview">
                    <a  class="m-video-link" id="play" data-toggle="modal"  data-target="#overviewVideo">
                        <div class="m-video-link--icon"><i class="fa fa-play" aria-hidden="true"></i></div>
                    </a>
                    <div class="modal fade" id="overviewVideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body mb-0 ">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <!-- 16:9 aspect ratio -->
                                    <div class="embed-responsive embed-responsive-16by9 z-depth-1-half">
	                                    <?php the_field( 'video_url' ); ?>
                                    </div>
                                </div>
                                <!--Footer-->
                                <div class="modal-footer justify-content-center text-white text-center flex-column flex-md-row">
                                    <button type="button" class="btn btn-outline-light btn-rounded btn-md ml-4"
                                            data-dismiss="modal">Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="oval-divider--bottom oval-divider--10"></div>
    </section>
</div><!-- SNF Oval Overlay Video CTA-->

<!-- Letter From REMY -->
<section class="executive-highlight highlight-section">
    <div class="container">
        <div class="row clearfix">
            <?php
            $args = array(
                'post_type' =>  'post',
                'posts_per_page' => 1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field' => 'slug',
                        'terms' => 'featured',
                    ),
                ),
            );
            $query = new WP_Query( $args ); ?>
            <?php if ( $query->have_posts() ) : ?>
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div class="col-sm-12 branded-title-seperation">
                        <section class="branded_title_container">
                            <h2 class="display-3">Pascal Remy</h2>
                            <h6 class="text-mute mt-md-3"><?php the_title();?></h6>
                        </section>
                    </div>
                    <div class="content-column col-md-7 col-sm-12 col-xs-12">
                        <div class="inner-column">
                            <div class="text"><p class="lead"><?php the_content();?></p></div>
                        </div>
                    </div>
                    <div class="image-column col-md-5 col-sm-12 col-xs-12">
                        <div class="inner-column " >
                            <div class="image">
                                <img src="<?php the_post_thumbnail_url('full') ?>" class="img-fluid rounded shadow-3 mb-1" alt="<?php the_title() ?>" loading="lazy">

                                <?php // the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile;  ?>
            <?php endif; wp_reset_query();?>
        </div>
    </div>
</section><!-- Remy Highlight SNF-->

<!-- Markets Cards w/Icons -->
<div class="markets-shutter site-outer">
    <?php get_template_part('template-assets/front-page/home-page-industry-panel-reveal');?>
</div><!-- Markets Shutter -->

<div class="py-md-3 py-lg-3  bg-light hp_cta wrap-cta-box">
    <div class="container">
        <div class="row d-flex p-3 align-items-center ">
	        <div class="col-sm-12 cta_subline">
		        <h3 class="my-3 text-uppercase text-dark display-4 text-center">Contact SNF Today!</h3>
		        <p class="lead text-center">Find out how SNF is investing towards a sustainable future today!</p>
	        </div>
            <div class="card shadow-sm border-0 ">
                <div class="row h-100">
                    <div class="col-md-4 col-sm-12 cta_image">
                        <img src="<?php bloginfo('template_directory'); ?>/resources/images/default-banner/snf-homepage-calltoaction-min.jpg" class="img-fluid rounded " alt="SNF Digital Graphic drawing attention to the Call to Action button"  loading="lazy"  />
                    </div>
                    <div class="col-md-8 col-sm-12 hp_cta_contact ">
                        <div class="row">
                            <div class="col border-right text-center">
                                <h3 class="mb-0 font-weight-bold">400K</h3>
                                <h6 class="text-muted font-weight-medium">End Users</h6>
                            </div>
                            <div class="col text-right border-left text-center">
                                <h3 class="mb-0 font-weight-bold">130</h3>
                                <h6 class="text-muted font-weight-medium">Countries Served</h6>
                            </div>
                            <div class="col-lg-12 mt-md-3 mt-1 text-center">
                                <div class="snf-link-wrapper ">
                                    <div class="snf-link">
                                        <a href="<?php echo home_url('/');?>contact" class="product-list-link">Connect With SNF</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Home Page News Slider -->
<div class="container">
    <div class="row mt-md-3 mb-md-3 my-sm-1">
        <div class="col-sm-12 news-title-seperation">
            <section class="news_title_container">
                <div class="heading "><h4 class="display-3">News & Events</h4></div>
            </section>
        </div>
    </div>
    <div class="row ">
        <div id="news_carousel" class="carousel slide w-100 mt-md-3 " data-ride="carousel">
            <?php
                $counter = 0;
                $args = array(
                    'post_type' =>  'global-communication',
                    'posts_per_page' => 6,
                    'order' => 'DESC',
                    'orderby' => 'DATE',
                );
                $query = new WP_Query( $args );
            ?>
            <div class="carousel-inner row  w-100 mx-auto " role="listbox">
                <?php if($query->have_posts()) :  $i = 0; // add this counter?>
                    <?php  while($query->have_posts()) : $query->the_post() ;?>
                        <div class="carousel-item col-md-4  <?php if ($query->current_post == 0):?> active <?php endif;?>">
                            <div class=" card h-100">
                                <?php if(has_post_thumbnail()):?>
                                    <div class="news_featured  "  style="background-image: url(<?php echo the_post_thumbnail_url("full") ;?>)"></div>
                                <?php else:?>
                                    <div class="news_featured_no_image  "  style="background-color:#002d73; ">
                                        <img src="<?php bloginfo('template_directory'); ?>/resources/images/logos/SNF-White.png" alt="SNF Logo" loading="lazy" class="img-fluid mx-auto d-block">
                                    </div>
                                <?php endif;?>
                                <div class="card-body">
                                    <h2 class="display-4 card-title"><?php the_title();?></h2>
                                </div>
                                <div class="card-footer bg-transparent border-0">
                                    <?php if(get_field('news_url_change')):?>
                                        <a href="<?php the_field('news_url_change');?>" class="btn btn-sm  btn-outline-dark">Read More</a>
                                    <?php else:?>
                                        <a class="btn btn-sm  btn-outline-dark" href="<?php the_permalink();?>">Read More</a>
                                    <?php endif;?>
                                </div>
                            </div><!--card-->
                        </div>
                    <?php $i++;?>
                    <?php endwhile; wp_reset_postdata(); ?>
                <?php endif;?>
            </div><!--carousel-inner-->
            <a class="left carousel-control-prev" href="#news_carousel" role="button" data-slide="prev">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                </svg>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control-next " href="#news_carousel" role="button" data-slide="next">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                </svg>
                <span class="sr-only">Next</span>
            </a>
        </div><!--col-->
    </div>
    <div class="row ">
        <div class="col-sm-12 text-center mt-md-5 mt-sm-5 pb-md-3 ">
            <div class="snf-link-wrapper ">
                <div class="snf-link">
                    <a  href="<?php echo home_url( '/' ); ?>news" class="product-list-link">View All News Articles</a>
                </div>
            </div>
        </div>
    </div><!--row-->
</div>


<?php get_footer(); ?>