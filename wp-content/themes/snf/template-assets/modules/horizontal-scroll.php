


	<div class="row mt-3 mb-3">
		<div class="col-sm-12 slider-scroll ">
            <section class="logo-box shadow-sm">
			<?php if ( have_rows( 'infinite_horizontal_scroll' ) ) : ?>
                <ul>
				    <?php while ( have_rows( 'infinite_horizontal_scroll' ) ) : the_row(); ?>
					    <?php $link = get_sub_field( 'link' ); ?>
					    <?php if ( $link ) : ?>
                            <li class="">
                                <a href="<?php echo esc_url( $link) ; ?>">
                                    <div class="scroll-container">
	                                    <?php $image = get_sub_field( 'image' ); ?>
	                                    <?php if ( $image ) : ?>
                                            <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="img-fluid" />
	                                    <?php endif; ?>

                                    </div>
                                </a>
                            </li>
                        <?php else:?>
                            <li class="">
                                <div class="scroll-container">
		                            <?php $image = get_sub_field( 'image' ); ?>
		                            <?php if ( $image ) : ?>
                                        <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="img-fluid" />
		                            <?php endif; ?>
                                </div>
                            </li>
					    <?php endif; ?>
				    <?php endwhile; ?>
                </ul>
			<?php else:?>
			<?php endif; ?>
            </section>
        </div>
    </div>


