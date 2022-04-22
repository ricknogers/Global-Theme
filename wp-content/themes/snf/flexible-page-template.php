<?php
/**
 * Template Name: Generic Flexible Content
 *

 */
get_header();?>
<div class="container global-overflow">
    <div class="row">
        <?php if(is_page('contact')):?>
            <div class="col-sm-8 col-xs-12 flexible-layout">
                <?php $counter = 0;  // check if the flexible content field has rows of data ?>
                <?php if ( have_rows( 'flexible_content' ) ): ?>
                    <?php while ( have_rows( 'flexible_content' ) ) : the_row(); ?>
                        <?php if ( get_row_layout() == 'card_layout' ) : ?>

                            <?php get_template_part('template-assets/modules/card-layout');?>

                        <?php elseif ( get_row_layout() == 'section_identifier' ) : ?>

                            <?php get_template_part('template-assets/modules/section-identifier');?>

                        <?php elseif ( get_row_layout() == 'split_image_text_layout' ) : ?>

                            <?php get_template_part('template-assets/modules/split-image-text-layout');?>

                        <?php elseif ( get_row_layout() == 'accordion_layout' ) : ?>

                            <?php get_template_part('template-assets/modules/accordion-repeater');?>

                        <?php elseif ( get_row_layout() == 'wysiwyg_layout' ) : ?>

                            <?php get_template_part('template-assets/modules/wysiwyg');?>

		                <?php elseif ( get_row_layout() == 'continous_horizontal_slide' ) : ?>

			                <?php get_template_part('template-assets/modules/horizontal-scroll');?>

                        <?php elseif ( get_row_layout() == 'contact_bar' ) : ?>

                            <?php get_template_part('template-assets/modules/contact-bar');?>

                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <?php // No layouts found ?>
                <?php endif; ?>
            </div>
            <div class="col-sm-4 col-xs-12 contact-sidebar">
                  <?php get_sidebar('contact');?>
            </div>
        <?php else: ?>
            <div class="col-sm-12 flexible-layout">
                <?php $counter = 0;  // check if the flexible content field has rows of data ?>
                <?php if ( have_rows( 'flexible_content' ) ): ?>
                    <?php while ( have_rows( 'flexible_content' ) ) : the_row(); ?>
                        <?php if ( get_row_layout() == 'card_layout' ) : ?>

                            <?php get_template_part('template-assets/modules/card-layout');?>

                        <?php elseif ( get_row_layout() == 'section_identifier' ) : ?>

                            <?php get_template_part('template-assets/modules/section-identifier');?>

                        <?php elseif ( get_row_layout() == 'split_image_text_layout' ) : ?>

                            <?php get_template_part('template-assets/modules/split-image-text-layout');?>

                        <?php elseif ( get_row_layout() == 'accordion_layout' ) : ?>

                            <?php get_template_part('template-assets/modules/accordion-repeater');?>

                        <?php elseif ( get_row_layout() == 'wysiwyg_layout' ) : ?>

                            <?php get_template_part('template-assets/modules/wysiwyg');?>

		                <?php elseif ( get_row_layout() == 'continous_horizontal_slide' ) : ?>

			                <?php get_template_part('template-assets/modules/horizontal-scroll');?>

                        <?php elseif ( get_row_layout() == 'contact_bar' ) : ?>

                            <?php get_template_part('template-assets/modules/contact-bar');?>

                        <?php endif; ?>
                    <?php endwhile; ?>
                <?php else: ?>
                    <?php // No layouts found ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php if(is_page('contact')):?>
    <?php // get_template_part('template-assets/conditional-features/contact-svg-map');?>
<?php endif; ?>

<?php get_footer();?>