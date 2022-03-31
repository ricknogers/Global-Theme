<?php if ( get_sub_field( 'include_contact_us_bar' ) == 1 ) : ?>
    <?php if ( get_sub_field( 'background_image' ) ) : ?>
        <section class="contact-bar bg-fixed text-white " style="background-image: url(<?php the_sub_field( 'background_image' ); ?>)">
            <div class="container">
                <header class="section-header">
                    <div class="text-center">
                        <h2><?php the_sub_field( 'title' ); ?></h2>
                    </div>
                    <hr>
                    <div class="text-center">
                        <?php $contact_link = get_sub_field( 'contact_link' ); ?>
                        <?php if ( $contact_link ) : ?>
                            <?php if(get_sub_field('button_text')):?>
                                <a class="btn btn-outline-light text-white" href="<?php echo esc_url( $contact_link) ; ?>"><?php the_sub_field('button_text');?></a>
                            <?php else:?>
                                <a class="btn btn-outline-light text-white" href="<?php echo esc_url( $contact_link) ; ?>">Contact Us</a>
                            <?php endif;?>
                        <?php endif; ?>
                    </div>
                </header>
            </div>
        </section>
    <?php else:?>
        <section class="contact-bar bg-fixed text-white bg-dark" style="background-image: url(<?php bloginfo('template_directory'); ?>/resources/images/CraterLakeHeroImage-scaled.jpg)">
            <div class="container">
                <header class="section-header">
                    <div class="text-center">
                        <h2><?php the_sub_field( 'title' ); ?></h2>
                    </div>
                    <hr>
                    <div class="text-center">
	                    <?php $contact_link = get_sub_field( 'contact_link' ); ?>
	                    <?php if ( $contact_link ) : ?>
		                    <?php if(get_sub_field('button_text')):?>
                                <a class="btn btn-outline-light text-white" href="<?php echo esc_url( $contact_link) ; ?>"><?php the_sub_field('button_text');?></a>
		                    <?php else:?>
                                <a class="btn btn-outline-light text-white" href="<?php echo esc_url( $contact_link) ; ?>">Contact Us</a>
		                    <?php endif;?>
	                    <?php endif; ?>
                    </div>
                </header>
            </div>
        </section>
    <?php endif ?>
<?php else : ?>
    <?php // echo 'false'; ?>
<?php endif; ?>