<?php if(has_post_thumbnail()):?>
<div class="row">
    <div class="col inner-banner-image default" style="background-image: url(<?php echo the_post_thumbnail_url("full") ;?>)">
        <div class=" breadcrumbs-container ">
            <div class="card shadow " style="background-color:#fff">
                <div class="card-body shadow-sm snf-breadcrumbs ">
                    <div class="col  crumbs">
                        <?php  if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
                    </div>
                </div><!--card-body shadow-sm snf-breadcrumbs-->
            </div><!--card shadow-->
        </div><!--breadcrumbs-container-->
    </div>
</div>
<div class="row">
    <div class="col ox">
        <?php if(get_field('secondary_title')):?>
            <div class="section-identifier two-titles">
                <h1><?php the_title();?><span><?php the_field( 'secondary_title' ); ?></span></h1>
            </div>
        <?php else:?>
            <div class="section-identifier one-title">
                <h1><?php the_title();?></h1>
            </div>
        <?php endif; ?>
    </div><!-- pageTitleOverlay-->
</div>
<?php else:?>
    	<?php get_template_part('/template-assets/header/breadcrumbs-page-title');?>

<?php endif;?>