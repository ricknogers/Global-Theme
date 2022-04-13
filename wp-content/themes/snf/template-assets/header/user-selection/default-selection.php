
<?php if(has_post_thumbnail() && !(has_term() || is_search() || is_archive() || is_singular())):?>
    <div class="row">
        <div class="col inner-banner-image default" style="background-image: url(<?php echo the_post_thumbnail_url("full") ;?>)"></div>
    </div>
    <?php get_template_part('/template-assets/header/user-selection/breadcrumbs');?>

    <?php get_template_part('/template-assets/header/user-selection/on-page-title');?>

<?php else:?>
    
	<?php get_template_part('/template-assets/header/user-selection/graphic-title-selection');?>
	<?php get_template_part('/template-assets/header/user-selection/breadcrumbs');?>

<?php endif;?>


