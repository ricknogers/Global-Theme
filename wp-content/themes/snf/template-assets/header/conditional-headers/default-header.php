<div class="" id="banner_elements">
<?php if(has_post_thumbnail() ):?>
    <div class="row">
        <div class="col inner-banner-image default" style="background-image: url(<?php echo the_post_thumbnail_url("full") ;?>)">
	        <?php if ( get_field( 'does_this_page_need_to_display_esg_report' ) == 1 ) : ?>
                <?php $esg_pdf_link = get_option('snf_esg_update_link');?>
                <?php $esg_title = get_option('snf_esg_link_title');?>
                <?php if ( $esg_pdf_link ) : ?>
                    <div class="sustain_highlight_box">
                        <div class="esg-overlay">
                            <div class="esg-content">
                                <a href="<?php echo $esg_pdf_link;?>"> <?php echo $esg_title; ?></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
	        <?php endif; ?>
        </div>
    </div>
    <?php get_template_part('/template-assets/header/conditional-headers/page-title-no-banner');?>

<?php else:?>
    
	<?php get_template_part('/template-assets/header/breadcrumbs-page-title');?>
<?php endif;?>

</div><!--banner_elements-->
