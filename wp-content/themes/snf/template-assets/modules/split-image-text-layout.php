<?php if ( have_rows( 'split_layout_repeater' ) ) : ?>
    <?php while ( have_rows( 'split_layout_repeater' ) ) : the_row(); ?>
        <?php if ($counter % 2 === 0) :?>
	        <div class="row split-spacing py-2 mb-2 highlight-section align-items-center">
	            <div class="content-column col-md-6 col-sm-12 col-xs-12">
	                <div class="inner-column left">
	                    <div class="sec-title"><?php $title = get_sub_field('title');?>
                            <h2 class="title"><?php echo $title; ?></h2>
	                    </div>
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
                                <div class="snf-link-wrapper ">
                                    <div class="snf-link">
                                        <?php if(get_sub_field('button_text')):?>
                                            <a href="<?php echo esc_url( $link); ?>" class="product-list-link"><?php the_sub_field('button_text');?></a>
                                        <?php else:?>
                                            <a href="<?php echo esc_url( $link); ?>" class="product-list-link">Read More</a>
                                        <?php endif;?>
                                    </div>
                                </div>
                            <?php else:?>
                            <?php endif; ?>
                        </div>
	                </div>
	            </div>
	            <!--Image Column-->
	            <div class="image-column col-md-6 col-sm-12 col-xs-12">
	                <div class="inner-column ">
	                    <div class="image w-100 shadow">
	                        <?php if ( get_sub_field( 'image' ) ) : ?>
	                            <img class="projcard-img img-fluid" src="<?php the_sub_field( 'image' ); ?>" />
	                        <?php endif ?>
	                    </div>
	                </div>
	            </div>
	        </div>
        <?php else:?>
            <div class="row split-spacing pt-3 pb-3 highlight-section align-items-center" id="">
                <div class="image-column col-md-6 col-sm-12 col-xs-12">
                    <div class="inner-column " >
                        <div class="image w-100 shadow">
                            <?php if ( get_sub_field( 'image' ) ) : ?>
                                <img class="projcard-img img-fluid" src="<?php the_sub_field( 'image' ); ?>" />
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <div class="content-column col-md-6 col-sm-12 col-xs-12">
                    <div class="inner-column right">
                        <div class="sec-title"><?php $title = get_sub_field('title');?>
                            <h2 class="title"><?php echo $title; ?></h2>
                        </div>
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
                                <div class="snf-link-wrapper ">
                                    <div class="snf-link">
				                        <?php if(get_sub_field('button_text')):?>
                                            <a href="<?php echo esc_url( $link); ?>" class="product-list-link"><?php the_sub_field('button_text');?></a>
				                        <?php else:?>
                                            <a href="<?php echo esc_url( $link); ?>" class="product-list-link">Read More</a>
				                        <?php endif;?>
                                    </div>
                                </div>
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