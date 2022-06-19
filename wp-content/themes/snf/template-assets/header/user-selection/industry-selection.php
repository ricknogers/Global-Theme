
<div class="row">
    <?php
    $terms = get_the_terms(get_the_ID(), 'markets');
    $cat_icon = get_field('hero_image', $terms);?>
    <?php if( $terms  && !has_post_thumbnail()): ?>
        <?php foreach( $terms as $term ): ?>
            <?php $icon = get_field('hero_image', $term->taxonomy . '_' . $term->term_id);?>
            <?php if($icon ):?>
                <div class="col inner-banner-image" style="background-image:url('<?php echo $icon['url']; ?>');">
                    
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

            <div class="col inner-banner-image default hh" style="background-image: url(<?php echo the_post_thumbnail_url("full") ;?>)"></div>
    <?php endif;?>
</div>
<?php get_template_part('/template-assets/header/user-selection/on-page-title');?>

