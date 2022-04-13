<?php if(  is_user_logged_in()):?>

    <?php
    $args = array(
        'post_type' =>  'document',
        'posts_per_page' => 1,
        'order' => 'ACS',
        'orderby'=> 'DATE',
        'tax_query' => array(
            array(
                'taxonomy' => 'investors',
                'field' => 'slug',
                'terms' => 'upcoming-events',
            ),
        ),
    );
    $query = new WP_Query( $args );?>
    <?php if(!empty($query)):?>
        <div class="row">
            <div class="col inner-banner-image X" style="background-image:  url(<?php echo the_post_thumbnail_url("full") ;?>)">
                <?php if ( $query->have_posts() ) : ?>
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <section class="investor-overlay"> </section>
                        <div class="col-sm-12">
                            <div class="investor-update text-center">
                                <?php the_content();?>
                            </div>
                        </div>
                    <?php endwhile; wp_reset_postdata();?>
                <?php endif;?>
            </div><!--inner-banner-image-->
        </div>
        <div class="row">
            <div class="col pageTitleOverlay">
                <h1><?php the_title();?></h1>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col breadcrumbs-container ">
                <div class="card shadow p-1">
                    <div class="card-body shadow-sm snf-breadcrumbs ">
                        <div class="col second-tier-nav crumbs">
							<?php  if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
                        </div>

                    </div><!--card-body shadow-sm snf-breadcrumbs-->
                </div><!--card shadow-->
            </div><!--breadcrumbs-container-->

        </div><!--row-->
    <?php endif;?>
<?php else:?>
    <div class="row">
        <div class="col page-header theme-bg-dark py-5 text-center position-relative">
            <div class="page-header-shapes-right "></div>
            <div class="page-header-shapes-left"></div>
            <div class="row">
                <div class="col page-header-title" >
                    <h1><?php the_title(); ?></h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col breadcrumbs-container ">
            <div class="card shadow p-1">
                <div class="card-body shadow-sm snf-breadcrumbs ">
                    <div class="col second-tier-nav crumbs">
						<?php  if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
                    </div>

                </div><!--card-body shadow-sm snf-breadcrumbs-->
            </div><!--card shadow-->
        </div><!--breadcrumbs-container-->

    </div><!--row-->

<?php endif;?>