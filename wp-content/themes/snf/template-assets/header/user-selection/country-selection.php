
    <div class="row">
        <?php if(has_post_thumbnail() && (is_page_template('subsidiary-landing.php') || is_page_template('flexible-page-template.php'))):?>
            <div class="col inner-banner-image default country-selection" style="background-image: url(<?php echo the_post_thumbnail_url("full") ;?>)">
<!--
                <div class=" breadcrumbs-container ">
                    <div class="card shadow " >
                        <div class="card-body shadow-sm snf-breadcrumbs ">
                            <div class="col  crumbs">
                                <?php  if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
                            </div>
                        </div><
                    </div>
                </div>
-->
            </div>
        <?php else:?>
        <?php endif;?>
    </div>
	<?php get_template_part('/template-assets/header/user-selection/on-page-title');?>


