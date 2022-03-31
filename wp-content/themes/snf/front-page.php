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
        <div class="col-sm-12 introduction mb-5 mt-5">
            <?php the_content();?>
        </div>
    </div>
</div>
<!-- SECTION -->
<div class="oval-overlay executive-highlight">
    <section class="geometric-overlay bg-img" style="background-image:url(<?php bloginfo('template_directory'); ?>/resources/images/shutterstock_327901652-min.jpg); min-height:500px">
        <div class="container p-5 d-flex h-100 ">
            <div class="row align-items-center overview-wrapper">
                <div class="col-md-8 overview">
                    <div class="clearfix">
                        <h2 class="text-white display-4">SNF Overview</h2>
                        <p class="lead text-white mt-3 mb-4">Learn more about what SNF does and all the industries we are involved in.</p>
                        <div class="snf-link-wrapper ">
                            <div class="snf-link">
                                <a href="<?php echo home_url('/');?>contact" class="product-list-link">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center overview video-overview">
                    <a  class="m-video-link" id="play" data-toggle="modal"  data-target="#overviewVideo">
                        <div class="m-video-link--icon"><i class="fa fa-play" aria-hidden="true"></i></div>
                    </a>
                    <div class="modal fade" id="overviewVideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-body mb-0 p-0">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <!-- 16:9 aspect ratio -->
                                    <div class="embed-responsive embed-responsive-16by9 z-depth-1-half">
                                        <iframe class="embed-responsive-item" src="https://player.vimeo.com/video/384048206?badge=0&autoplay=1&loop=1" id="player"  frameborder="0" allowscriptaccess="always"  allowfullscreen></iframe>
                                    </div>
                                </div>
                                <script>
                                         jQuery(document).ready(function($){
                                            $("element").data('bs.modal')?._isShown    // Bootstrap 4
                                            $('#overviewVideo').on('hidden.bs.modal', function(){
                                                if($('.modal.show').length){
                                                }
                                                else{
                                                    $('#overviewVideo iframe').removeAttr('src');
                                                }
                                            });
                                         });
                                </script>
                                <!--Footer-->
                                <div class="modal-footer justify-content-center text-white text-center flex-column flex-md-row">
                                    <span class="mr-4">Spread the word!</span>
                                    <div>
                                        <a type="button" class="btn-floating btn-sm btn-fb"><i class="fa fa-facebook-square" aria-hidden="true"></i></a>
                                        <a type="button" class="btn-floating btn-sm btn-gplus"><i class="fa fa-vimeo-square"></i></a>
                                        <a type="button" class="btn-floating btn-sm btn-ins"><i class="fa fa-linkedin-square"></i></a>
                                    </div>
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
            $cats = get_categories();
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
                    <div class="content-column col-md-7 col-sm-12 col-xs-12">
                        <div class="inner-column">
                            <div class="sec-title">
                                <div class="title">Pascal Remy</div>
                                <h2><?php the_title();?></h2>
                            </div>
                            <div class="text"><p class="lead"><?php the_content();?></p></div>
                            <div class="snf-link-wrapper ">
                                <div class="snf-link">
                                    <a href="<?php echo home_url('/');?>about" class="product-list-link">Read More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="image-column col-md-5 col-sm-12 col-xs-12">
                        <div class="inner-column " >
                            <div class="image">
                                <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
                                <div class="overlay-box">
                                    <div class="year-box"><span class="number">25</span>Years <br> Experience <br> Working</div>
                                </div>
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

<!-- CTA -->
<div class="py-5  bg-light service-34 wrap-service34-box">
    <!-- Row  -->
    <div class="container">
        <div class="row d-flex p-3 align-items-center ">
            <div class="col-sm-6 ">
                <img src="<?php bloginfo('template_directory'); ?>/resources/images/default-banner/fallback-innerpage-banner_1.jpg" class="img-fluid rounded " alt="" />
            </div>
            <div class="col-sm-6 ">
                <div class="">
                    <span class="badge badge-info rounded-pill px-3 py-1 font-weight-light">Sustainability</span>
                    <h3 class="my-3 text-uppercase text-dark display-4">Contact SNF Today!</h3>
                    <p class="lead">Find out how SNF is investing towards a sustainable future today!</p>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card card-shadow border-0 mb-3">
                                <div class="card-body">
                                    <div class="row p-3">
                                        <div class="col-6 border-right">
                                            <h3 class="mb-0 font-weight-light">400K</h3>
                                            <h6 class="text-muted font-weight-light">End Users</h6>
                                        </div>
                                        <div class="col-6 text-right border-left">
                                            <h3 class="mb-0 font-weight-light">130</h3>
                                            <h6 class="text-muted font-weight-light">Countries Served</h6>
                                        </div>
                                        <div class="col-lg-12 mt-3 text-center">
                                            <div class="snf-link-wrapper ">
                                                <div class="snf-link">
                                                    <a href="<?php echo home_url('/');?>about" class="product-list-link">Connect With SNF</a>
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
        </div>
    </div>
</div>
<!-- CTA -->

<!-- News SECTION -->
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="newsEvents">
                <div class="col-sm-12 homeNewsWrapper">
                    <div class="newsTitleWrapper">
                        <h2 class="homeNewsSectionTitle">News & Events</h2>
                    </div>
                    <?php
                    $args = array(
                        'post_type' =>  'global-communication',
                        'posts_per_page' => 4,
                        'order' => 'ASC',
                        'orderby' => 'DATE',
                    );
                    $query = new WP_Query( $args );?>
                    <section class="slide-wrapper">
                        <div class="containerSlider">
                            <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                <!-- Wrapper for slides -->
                                <div class="carousel-inner">
                                    <?php
                                    $args = array(
                                        'post_type' =>  'global-communication',
                                        'posts_per_page' => 4,
                                        'order' => 'ASC',
                                        'orderby' => 'DATE',
                                    );
                                    $query = new WP_Query( $args );?>
                                    <?php if($query->have_posts()) : ?>
                                        <?php $counter = 0;?>
                                        <?php while($query->have_posts()) : $query->the_post() ?>
                                            <div class="carousel-item <?php if ($counter === 0):?> active <?php endif;?>">
                                                <div class="fill row" >
                                                    <div class="fillContent col-lg-6 col-sm-12">
                                                        <h2><?php the_title();?></h2>
                                                        <?php if(get_field('news_url_change')):?>
                                                            <div class="snf-link-wrapper ">
                                                                <div class="snf-link">
                                                                    <a href="<?php the_field('news_url_change');?>" class="product-list-link">Read More</a>
                                                                </div>
                                                            </div>
                                                        <?php else:?>
                                                             <div class="snf-link-wrapper ">
                                                                <div class="snf-link">
                                                                    <a href="<?php the_permalink();?>" class="product-list-link">Read More</a>
                                                                </div>
                                                            </div>
                                                        <?php endif;?>
                                                    </div>
                                                    <?php if(has_post_thumbnail()):?>
                                                        <div class="fillImage col-lg-6 col-sm-12" style="background-image: url(<?php echo the_post_thumbnail_url("full") ;?>);">
                                                            <span class="news-type">
                                                                <?php  the_category(); ?>
                                                            </span>
                                                        </div>
                                                    <?php else:?>
                                                        <div class="fillImage col-lg-6 col-sm-12" style="background-image:url(<?php bloginfo('template_directory'); ?>/images/news/news_fallback_<?php echo rand(1, 4); ?>.jpg);">
                                                            <span class="news-type">
                                                                <?php  the_category(); ?>
                                                            </span>
                                                        </div>
                                                    <?php endif;?>
                                                </div>
                                            </div>
                                            <?php $counter++; ?>
                                        <?php endwhile; wp_reset_postdata(); ?>
                                    <?php endif;?>
                                </div>
                                <div class="col-sm-12">
                                    <ol class="carousel-indicators">
                                        <?php if ( $query->have_posts() ) : ?>
                                            <?php $counter = 0;?>
                                            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                                <!-- Indicators -->
                                                <li data-target="#myCarousel" data-slide-to="<?php echo $counter ?>" class=" <?php if ($counter === 0):?> active <?php endif;?>"></li>
                                                <?php $counter++; ?>
                                            <?php endwhile;  ?>
                                        <?php endif; wp_reset_postdata();?>
                                    </ol>
                                </div>
                            </div>
                    </section>
                    <div class="col-sm-12 text-center mt-5 pb-3">
                         <div class="snf-link-wrapper ">
                            <div class="snf-link">
                                <a  href="<?php echo home_url( '/' ); ?>news" class="product-list-link">View All News Articles</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--container-->
<?php get_footer(); ?>