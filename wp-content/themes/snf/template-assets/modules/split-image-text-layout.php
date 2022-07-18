<?php if ( have_rows( 'split_layout_repeater' ) ) : ?>
    <?php while ( have_rows( 'split_layout_repeater' ) ) : the_row(); ?>
    	<?php $counter == 0 ?>
        <?php if ($counter % 2 === 0) :?>
	        <div class="row split-spacing my-3 highlight-section align-items-center">
	            <div class="content-column col-md-7 col-lg-7 col-xl-6 col-sm-12">
	                <div class="inner-column left">
		                <?php $title = get_sub_field('title');?>
                        <?php if($title):?>
                            <div class="sec-title">
                                <h2 class="title"><?php echo $title; ?></h2>
                            </div>
                        <?php endif;?>
	                    <div class="text">
                            <?php if (get_sub_field( 'content_length' ) == 'excerpt' ) : ?>
                                <p><?php echo snf_custom_excerpt(get_sub_field('content'));?></p>
                            <?php else:?>
                                <?php if (get_sub_field( 'content_length' ) == 'full-length' ) : ?>
                                    <p><?php the_sub_field('content');?></p>
                                <?php endif;?>
                            <?php endif;?>
		                    <?php $link = get_sub_field( 'link' ); ?>
                            <?php if ( $link ):?>
                                <?php if(get_sub_field('link')):?>
                                    <a href="<?php echo esc_url( $link); ?>" target="<?php echo esc_attr( $link_target ); ?>" class="page-linkage"><?php the_sub_field( 'link_title' ); ?> <i class="bi bi-chevron-double-right"></i></a>
                                <?php else:?>
                                    <a href="<?php echo esc_url( $link); ?>" target="<?php echo esc_attr( $link_target ); ?>" class="page-linkage">Read More <i class="bi bi-chevron-double-right"></i></a>
                                <?php endif;?>
                            <?php else:?>
                            <?php endif; ?>
                        </div>
	                </div>
	            </div>
	            <div class="image-column col-md-5 col-lg-5 col-xl-6 d-xs-none">
	                <div class="inner-column ">
	                    <div class="image w-100 shadow">
	                        <?php if ( get_sub_field( 'image' ) ) : ?>
                                <img src="<?php the_sub_field( 'image' ); ?>" class="projcard-img img-fluid rounded shadow-3 mb-1" alt="<?php the_title() ?>" loading="lazy">
	                        <?php endif ?>
	                    </div>
	                </div>
	            </div>
	        </div>
        <?php else:?>
            <div class="row split-spacing pt-3 pb-3 highlight-section align-items-center" id="">
                <div class="image-column col-md-5 col-lg-5 col-xl-6 d-xs-none order-first">
                    <div class="inner-column " >
                        <div class="image w-100 shadow">
                            <?php if ( get_sub_field( 'image' ) ) : ?>
                                <img src="<?php the_sub_field( 'image' ); ?>" class="projcard-img img-fluid rounded shadow-3 mb-1" alt="<?php the_title() ?>" loading="lazy">

                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="content-column col-md-7 col-lg-7 col-xl-6 col-sm-12 order-last">
                    <div class="inner-column right">
			            <?php $title = get_sub_field('title');?>
			            <?php if($title):?>
                            <div class="sec-title">
                                <h2 class="title"><?php echo $title; ?></h2>
                            </div>
			            <?php endif;?>
                        <div class="text">
				            <?php if (get_sub_field( 'content_length' ) == 'excerpt' ) : ?>
                                <p><?php echo snf_custom_excerpt(get_sub_field('content'));?></p>
				            <?php else:?>
					            <?php if (get_sub_field( 'content_length' ) == 'full-length' ) : ?>
                                    <p><?php the_sub_field('content');?></p>
					            <?php endif;?>
				            <?php endif;?>
				            <?php $link = get_sub_field( 'link' ); ?>
				            <?php if ( $link ) : ?>

					            <?php if($link):?>
                                    <a href="<?php echo esc_url( $link); ?>" target="<?php echo esc_attr( $link_target ); ?>" class="page-linkage"><?php the_sub_field( 'link_title' ); ?> <i class="bi bi-chevron-double-right"></i></a>
					            <?php else:?>
                                    <a href="<?php echo esc_url( $link); ?>" target="<?php echo esc_attr( $link_target ); ?>" class="page-linkage">Read More <i class="bi bi-chevron-double-right"></i></a>
					            <?php endif;?>

				            <?php else:?>
				            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php $counter++; ?>
    <?php endwhile; ?>
<?php else : ?>
    <?php // No rows found ?>
<?php endif; ?>