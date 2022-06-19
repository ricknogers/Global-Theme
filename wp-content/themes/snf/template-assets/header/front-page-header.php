<div class="container-fluid">
    <div class="row front-page-hero-wrapper">
        <?php
        $the_query = new WP_Query(array(
            "post_type" => "hero",
            "posts_per_page" =>1,
        ));
        while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
            <div class="wrapper ">
                <section class="splitCard">
                    <style>
                        @media (min-width: 992px){
                            .card__img{
                                background-size: cover;
                                background-repeat: no-repeat;
                                background-position: center;
                                position: relative;
                                padding:0;
                                background-image:url("<?php echo the_post_thumbnail_url("full") ;?>");
                                /*clip-path: polygon(0% 0%, 75% 0%, 100% 0, 91% 100%, 0% 100%);*/
                            }
                        }
                        @media (max-width: 991px){
                            .card__img{
                               display:none;
                                /*clip-path: polygon(0% 0%, 75% 0%, 100% 0, 91% 100%, 0% 100%);*/
                            }
                        }
                    </style>
                    <div class="card__img col-lg-12 col-sm-12 ripple-container" id=" ">
                        <div class="card__content " >
                            <div class="hero_banner_content">
                                <div class="header-content">
                                    <section>
                                        <div class="hero_content_relative">
                                            <h1> <?php echo get_option('company_name') ;?></h1>
                                            <?php if(get_field('banner_excerpt')):?>
                                                <?php the_field('banner_excerpt');?>
                                            <?php endif;?>
                                            <?php if(get_field('banner_button_text')):?>
                                                <div class="snf-link-wrapper ">
                                                    <div class="snf-link">
                                                        <a href="<?php the_field('button_link');?>" class="product-list-link" ><?php the_field('banner_button_text');?></a>
                                                    </div>
                                                </div><!--snf-link-wrapper-->
                                            <?php endif;?>
                                        </div>
                                    </section>
                                </div><!--header-content-->
                            </div>
                        </div> <!-- .card__content -->
                        <div class="title-bar">
                            <div class="title-bar-text">
                                <h2 class="skinnyTitle"><?php echo get_option('corporate_tag_line') ;?></h2>
                            </div>
                        </div>
                    </div><!-- .card__img -->
                </section> <!-- .card -->
            </div> <!-- .wrapper -->
        <?php endwhile; wp_reset_postdata(); ?>
    </div><!--front-page-hero-wrapper-->
    <div class="row " id="mobile-title">
        <div class="header-site-title col">
            <h1><?php echo get_option('company_name') ;?> </h1>
        </div><!--header-site-title-->
    </div><!--mobile-title-->
</div><!--container-fluid-->