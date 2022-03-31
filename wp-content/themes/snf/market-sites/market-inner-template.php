<?php
/**
 * Template Name: Industry Flexible Layout
 *

 **/
get_header();?>

<div class="container">
    <div class="row">
        <div class="col-md-9 col-sm-12 left-side-sidebar border-right">
            <!---Different Header Rendered -->
            <div class="row">
                <div class="col ">
                    <div class="section-identifier one-title">
                        <h1><?php the_title();?></h1>
                    </div>
                </div><!-- pageTitleOverlay-->
            </div>
            <div class="row">
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

                            <?php elseif ( get_row_layout() == 'contact_bar' ) : ?>

                                <?php get_template_part('template-assets/modules/contact-bar');?>

                            <?php endif; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <?php // No layouts found ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-12 right-side-sidebar">
            <?php get_sidebar('');?>
        </div><!--right-side-sidebar-->
    </div>
</div>


<script>
    (function($, window){
        $(document).ready(function(){
          
        });

    })(jQuery, window);
</script>
<?php get_footer();?>

