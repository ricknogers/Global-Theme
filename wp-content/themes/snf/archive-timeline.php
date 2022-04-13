<?php

get_header();?>
    <div class="container">
        <div class="row timeline-container">
            <div class="col-sm-12 timeline">
                <div class="swiper-container">
                    <?php
                    $args = array(
                        'post_type' =>  'timeline',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'meta_key'			=> 'timeline_year',
                        'orderby'			=> 'meta_value',
                        'order'				=> 'ASC'
                    );
                    $query = new WP_Query( $args );
                    ?>
                    <?php $count = 0; if ( $query->have_posts() ) : ?>
                        <div class="swiper-wrapper">
                            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                                <div class="swiper-slide" style="background-image: url(<?php echo the_post_thumbnail_url("full") ;?>)" data-year="<?php echo $count++?>">
                                    <div class="swiper-slide-content"><span class="timeline-year"><?php the_field('timeline_year');?></span>
                                        <?php if(get_field('title_of_event')):?>
                                            <h4 class="timeline-title"><?php the_field('title_of_event');?></h4>
                                        <?php endif; ?>
                                        <?php if(get_field('timeline_description')):?>
                                            <p class="timeline-text"><?php the_field('timeline_description');?></p>
                                        <?php endif; ?>
                                    </div><!--swiper-slide-content-->
                                </div><!--swiper-slide-->
                            <?php endwhile; wp_reset_postdata();?>
                        </div><!--swiper-wrapper-->
                    <?php endif; ?>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-pagination"></div>
                </div><!--swiper-container-->
            </div>
        </div>
    </div>
    <script>
        (function($, window){
            $(document).ready(function(){
                var timelineSwiper = new Swiper ('.timeline .swiper-container', {
                    direction: 'vertical',
                    loop: true,
                    speed: 1600,
                    pagination: '.swiper-pagination',
                    paginationBulletRender: function (swiper, index, className) {
                        var year = document.querySelectorAll('.swiper-slide')[index].getAttribute('data-year');
                        return '<span class="' + className + '">' + year + '</span>';
                    },
                    paginationClickable: true,
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    breakpoints: {
                        768: {
                            direction: 'horizontal',
                        }
                    }
                });
            });
        })(jQuery, window);
    </script>
<?php get_footer();?>