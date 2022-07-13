<?php if ( get_sub_field( 'include_contact_us_bar' ) == 1 ) : ?>
    <?php if ( get_sub_field( 'background_image' ) ) : ?>
        <section class="contact-bar bg-fixed text-white d-md-none d-lg-block " >
            <div class="container p-0">
                <div class="contact_bg_bar" style="background-image: url(<?php the_sub_field( 'background_image' ); ?>)" loading="lazy">
                    <header class="section-header">
                        <div class="text-center">
                            <h2><?php the_sub_field( 'title' ); ?></h2>
                        </div>
                        <div class="text-center">
                            <?php $contact_link = get_sub_field( 'contact_link' ); ?>
                            <?php if ( $contact_link ) : ?>
                                <?php if(get_sub_field('button_text')):?>
                                    <a class="btn btn-outline-light text-white" href="<?php echo esc_url( $contact_link) ; ?>"><?php the_sub_field('button_text');?></a>
                                <?php else:?>
                                    <a class="btn btn-outline-light text-white" href="<?php echo esc_url( $contact_link) ; ?>">Contact Us</a>
                                <?php endif;?>
                            <?php endif; ?>
                        </div><!--text-center-->
                    </header>
                </div><!--contact_bg_bar-->
            </div><!--container-->
        </section><!--contact-bar-->
    <?php else:?>
        <section class="contact-bar bg-fixed text-white bg-dark d-md-none d-lg-block">
            <div class="container p-0">
                <div class="contact_bg_bar" style="background-image: url(<?php bloginfo('template_directory'); ?>/resources/images/CraterLakeHeroImage-scaled.jpg)" loading="lazy">
                    <header class="section-header">
                        <div class="text-center">
                            <h2><?php the_sub_field( 'title' ); ?></h2>
                        </div><!--text-center-->
                        <div class="text-center">
                            <?php $contact_link = get_sub_field( 'contact_link' ); ?>
                            <?php if ( $contact_link ) : ?>
                                <?php if(get_sub_field('button_text')):?>
                                    <a class="btn btn-outline-light text-white" href="<?php echo esc_url( $contact_link) ; ?>"><?php the_sub_field('button_text');?></a>
                                <?php else:?>
                                    <a class="btn btn-outline-light text-white" href="<?php echo esc_url( $contact_link) ; ?>">Contact Us</a>
                                <?php endif;?>
                            <?php endif; ?>
                        </div><!--text-center-->
                    </header>
                </div><!--contact_bg_bar-->
            </div><!--container-->
        </section><!--contact-bar-->
    <?php endif ?>
<?php else : ?>
    <?php // echo 'false'; ?>
<?php endif; ?>