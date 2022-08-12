<?php
/**
 * Template Name: Country Landing Page
 *

 */

get_header(); ?>
<div class="container global-overflow">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12 subs-flexible-wrapper mt-2 mb-3">
            <?php $counter = 0;  // check if the flexible content field has rows of data ?>
            <?php if ( have_rows( 'flexible_modules' ) ): ?>
                <?php while ( have_rows( 'flexible_modules' ) ) : the_row(); ?>
                    <?php if ( get_row_layout() == 'card_layout' ) : ?>
                        <div class="row certification-row">
                            <div class="col-sm-12">
                                <?php if(get_sub_field('card_layout_title')):?>
                                    <h2 class="layout-title"><?php the_sub_field( 'card_layout_title' ); ?></h2>
                                <?php endif; ?>
                            </div>
	                        <?php if(get_sub_field('card_description')):?>
                            <div class="col-md-6 col-sm-12 h-100 certificate-info">

                                    <p ><?php the_sub_field( 'card_description' ); ?></p>

                            </div>
                                <div class="col-md-6 col-sm-12 h-100 certificate-link">
			                        <?php if ( have_rows( 'card_repeater' ) ) : $i = 0;?>
                                        <div class="list-group ">
					                        <?php while ( have_rows( 'card_repeater' ) ) : the_row(); $i++; ?>
						                        <?php $link = get_sub_field( 'link' ); ?>
						                        <?php if ( $link ) : ?>
                                                    <a class=" list-group-item list-group-item-action " href="<?php echo esc_url( $link) ; ?>" target="_blank">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h3 class="card-title pt-1"><?php the_sub_field( 'title' ); ?></h3>
                                                            <i class="certification-icon bi bi-filetype-pdf"></i>
                                                        </div>
                                                    </a>
						                        <?php else:?>
                                                    <a class=" list-group-item list-group-item-action " href="#" target="">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h3 class="card-title pt-1"><?php the_sub_field( 'title' ); ?></h3>
                                                            <i class="certification-icon bi bi-filetype-pdf"></i>
                                                        </div>
                                                    </a>
						                        <?php endif; ?>
					                        <?php endwhile; ?>
                                        </div><!--certification-row-->
			                        <?php else : ?>
				                        <?php // No rows found ?>
			                        <?php endif; ?>
                                </div><!--certificate-link-->
                            <?php else:?>
                                <div class="col-md-6 col-sm-12 h-100 certificate-link">
			                        <?php if ( have_rows( 'card_repeater' ) ) : $i = 0;?>
                                        <div class="list-group ">
					                        <?php while ( have_rows( 'card_repeater' ) ) : the_row(); $i++; ?>
						                        <?php $link = get_sub_field( 'link' ); ?>
						                        <?php if ( $link ) : ?>
                                                    <a class=" list-group-item list-group-item-action " href="<?php echo esc_url( $link) ; ?>" target="_blank">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h3 class="card-title pt-1"><?php the_sub_field( 'title' ); ?></h3>
                                                            <i class="certification-icon bi bi-filetype-pdf"></i>
                                                        </div>
                                                    </a>
						                        <?php else:?>
                                                    <a class=" list-group-item list-group-item-action " href="#" target="">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <h3 class="card-title pt-1"><?php the_sub_field( 'title' ); ?></h3>
                                                            <i class="certification-icon bi bi-filetype-pdf"></i>
                                                        </div>
                                                    </a>
						                        <?php endif; ?>
					                        <?php endwhile; ?>
                                        </div><!--certification-row-->
			                        <?php else : ?>
				                        <?php // No rows found ?>
			                        <?php endif; ?>
                                </div><!--certificate-link-->
	                        <?php endif; ?>

                      </div><!--certification-row-->
                    <?php elseif ( get_row_layout() == 'split_image_text_layout' ) : ?>
                        <?php if ( have_rows( 'split_layout_repeater' ) ) : ?>
                            <?php while ( have_rows( 'split_layout_repeater' ) ) : the_row(); ?>
                                <?php if ($counter % 2 === 0) :?>
                                    <div class=" split-container my-md-5">
                                        <div class="row layered-bg-behind shadow">
                                            <div class=" col-md-6 col-sm-12 split-content p-md-5 p-3">
                                                <h2> <?php the_sub_field( 'title' ); ?></h2>
                                                <?php the_sub_field( 'content' ); ?>
                                                <?php $link = get_sub_field( 'link' ); ?>
                                                <?php if ( $link ) : ?>
                                                    <a href="<?php echo esc_url( $link) ; ?>" class="split-link">
                                                        <img src="<?php bloginfo('template_directory'); ?>/resources/images/icons/arrow-thin-right.svg" alt="Arrow Link to Continue to Content" class="img-fluid "  loading="lazy">
                                                    </a>
                                                <?php endif; ?>
                                            </div><!--split-content-->
                                            <?php if ( get_sub_field( 'image' ) ) : ?>
                                                <div class="col-md-6 d-xs-none split-imagery " style="background-image: url(<?php the_sub_field('image')?>)">
                                                    

                                                </div><!--split-imagery-->
                                            <?php endif ?>
                                        </div><!--layered-bg-behind-->
                                    </div><!--split-container-->
                                <?php else:?>
                                    <div class=" split-container my-md-5">
                                        <div class="row layered-bg-behind shadow">
                                            <?php if ( get_sub_field( 'image' ) ) : ?>
                                                <div class="col-md-6 d-xs-none split-imagery " style="background-image: url(<?php the_sub_field('image')?>)"></div>
                                            <?php endif ?>
                                            <div class="col-md-6 col-sm-12 split-content p-md-5 p-3">
                                                <h2> <?php the_sub_field( 'title' ); ?></h2>
                                                <?php the_sub_field( 'content' ); ?>
                                                <?php $link = get_sub_field( 'link' ); ?>
                                                <?php if ( $link ) : ?>
                                                    <a href="<?php echo esc_url( $link) ; ?>" class="split-link">
                                                        <img src="<?php bloginfo('template_directory'); ?>/resources/images/icons/arrow-thin-right.svg" alt="Arrow Link to Continue to Content" class="img-fluid "  loading="lazy">
                                                    </a>
                                                <?php endif; ?>
                                            </div><!--split-content-->
                                        </div><!--layered-bg-behind-->
                                    </div><!--split-container-->
                                <?php endif;?>
                                <?php $counter++; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <?php // No rows found ?>
                        <?php endif; ?>
                    <?php elseif ( get_row_layout() == 'accordion_layout' ) : ?>
                        <?php get_template_part('template-assets/modules/accordion-repeater');?>
                    <?php elseif ( get_row_layout() == 'wysiwyg' ) : ?>
                        <div class="subs-content element pt-3" >
                            <?php the_sub_field('wysiwyg');     ?>
                        </div>
                        <hr class="center-diamond">
                    <?php elseif ( get_row_layout() == 'section_identifier' ) : ?>
                        <?php if(get_sub_field('secondary_title') && get_sub_field('primary_title')):?>
                            <div class="section-identifier two-titles">
                                <h2><?php the_sub_field( 'primary_title' ); ?><span><?php the_sub_field( 'secondary_title' ); ?></span></h2>
                            </div>
                        <?php else:?>
                            <div class="section-identifier one-title">
                                <h2><?php the_sub_field( 'primary_title' ); ?></h2>
                            </div>
                        <?php endif;?>
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
<?php get_footer(); ?>