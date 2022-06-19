
    <div class="row">
        <?php if(has_post_thumbnail() && (is_page_template('subsidiary-landing.php') || is_page_template('flexible-page-template.php'))):?>
            <div class="col inner-banner-image default country-selection" style="background-image: url(<?php echo the_post_thumbnail_url("full") ;?>)">
                <div class=" breadcrumbs-container ">
                    <!-- Functions Checks Page to see if there are any markets terms selected for page // returns $term_list -->
                    <div class="card shadow " >
                        <div class="card-body shadow-sm snf-breadcrumbs ">
                            <div class="col  crumbs">
                                <?php  if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
                            </div>
                        </div><!--card-body shadow-sm snf-breadcrumbs-->
                    </div><!--card shadow-->
                </div><!--breadcrumbs-container-->
            </div>
        <?php else:?>
        <?php endif;?>
    </div>
	<?php get_template_part('/template-assets/header/user-selection/on-page-title');?>


