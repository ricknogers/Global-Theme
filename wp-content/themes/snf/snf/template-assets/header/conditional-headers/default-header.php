<div class="" id="banner_elements">
<?php if(has_post_thumbnail()):?>
    <div class="row">
        <div class="col inner-banner-image default" style="background-image: url(<?php echo the_post_thumbnail_url("full") ;?>)">
	        <?php if ( get_field( 'does_this_page_need_to_display_esg_report', 'option' ) == 1 ) : ?>
                <?php if ( have_rows( 'esg_pages', 'option' ) ) : ?>
                    <?php while ( have_rows( 'esg_pages', 'option' ) ) : the_row(); ?>
                        <?php $esg_pdf_link = get_sub_field( 'esg_pdf_link' ); ?>
                        <?php $link_title = $esg_pdf_link['title'];?>
                        <?php $id_check = get_the_ID();?>
                        <?php $pages_to_populate = get_sub_field( 'pages_to_populate' ); ?>
                        <?php if ( $pages_to_populate ) : ?>
                            <?php foreach ( $pages_to_populate as $post_ids ) : ?>
                                <div class="sustain_highlight_box">
                                    <div class="esg-overlay">
                                        <div class="esg-content">
                                            <?php if($post_ids == $id_check ):?>
                                                <a href="<?php echo get_permalink( $post_ids ); ?>"> <?php echo $link_title; ?></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php endif; ?>
	        <?php else : ?>
		        <?php // echo 'false'; ?>
	        <?php endif; ?>
        </div>
    </div>
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
    </div><!--row-->
<?php endif;?>
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
