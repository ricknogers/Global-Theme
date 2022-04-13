<?php if(!is_search()):?>
<div class="row">
    <?php
    $terms = get_the_terms(get_the_ID(), 'markets');
    $cat_icon = get_field('hero_image', $queried_object);?>
    <?php if( $terms  ): ?>
        <?php foreach( $terms as $term ): ?>
            <?php $icon = get_field('hero_image', $term->taxonomy . '_' . $term->term_id);?>
            <?php if($icon):?>
                <div class="col inner-banner-image" style="background-image:url('<?php echo $icon['url']; ?>');">
                    <div class=" breadcrumbs-container ">
                        <!-- Functions Checks Page to see if there are any markets terms selected for page // returns $term_list -->
                       
                        <?php foreach($terms as $term) :?>
                            <div class="card shadow " >
                                <div class="card-body shadow-sm snf-breadcrumbs ">
                                    <div class="col  crumbs">
                                        <?php  if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
                                    </div>
                                </div><!--card-body shadow-sm snf-breadcrumbs-->
                            </div><!--card shadow-->
                        <?php endforeach; ?>
                    </div><!--breadcrumbs-container-->
                </div>
            <?php else:?>
                <?php if(has_post_thumbnail()):?>
                    <div class="col inner-banner-image default " style="background-image: url(<?php echo the_post_thumbnail_url("full") ;?>)"></div>
                <?php else:?>
                    <?php get_template_part('/template-assets/header/user-selection/graphic-title-selection');?>

					<?php get_template_part('/template-assets/header/user-selection/on-page-title');?>
                <?php endif;?>
            <?php endif;?>
        <?php endforeach; ?>
    <?php else:?>
    <?php endif;?>
</div>
<?php get_template_part('/template-assets/header/user-selection/on-page-title');?>


<?php endif;?>