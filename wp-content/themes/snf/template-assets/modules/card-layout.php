<?php if(get_sub_field('card_layout_title')):?>
    <div class="row">
        <div class="col-sm-12 card-title-identify">
            <h2 class="layout-title"><?php the_sub_field( 'card_layout_title' ); ?></h2>
        </div>
    </div>
<?php endif; ?>
<?php if(get_sub_field('card_description')):?>
    <div class="row">
        <div class="col-sm-12 h-100 certificate-info card-description-identify">
            <?php the_sub_field( 'card_description' ); ?>
        </div>
    </div>
<?php endif; ?>
<?php if (get_sub_field( 'card_layout' ) == 'card-slider' ) : ?>
	<?php if(has_term('','markets' )):?>
		<?php get_template_part('/market-sites/get-market-application-useage');?>
	<?php else:?>
		<div class="row mb-5 mt-2">
		    <div id="markets_carousel" class="carousel slide col-sm-12 mt-3 mb-3" data-ride="carousel">
				<div class="row mb-3">
		            <div class="col-sm-12">
				        <a class="left carousel-control-prev" href="#markets_carousel" role="button" data-slide="prev">
				            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
				            	<path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
				            </svg>
				            <span class="sr-only">Previous</span>
				        </a>
				        <a class="right carousel-control-next " href="#markets_carousel" role="button" data-slide="next">
				            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
				                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
				            </svg>
				            <span class="sr-only">Next</span>
				        </a>
				    </div><!--col-sm-12-->
		        </div><!--row-->
		        <div class="row carousel-inner w-100 mx-auto" role="listbox">
			        <?php $i == 0; if ( have_rows( 'card_repeater' ) ) : ?>
				        <?php while ( have_rows( 'card_repeater' ) ) : the_row(); ?>
		                    <div class="carousel-item col-md-4 col-sm-12 <?php if ($i == 0):?> active <?php endif;?>">
		                        <div class="cta_card_slider card h-100 shadow-sm">
			                        <?php $image = get_sub_field('image');?>
									<?php $title = get_sub_field('title');?>
					                <?php if (get_sub_field( 'image_type' ) == 'background-image' ) : ?>
		                                <div class="bg_image_card" style="background-image: url(<?php echo $image['url']; ?>)"></div>
									<?php else:?>
						                <?php if (get_sub_field( 'image_type' ) == 'responsive-image' ) : ?>
		                                    <div class="card-img-top">
		                                        <img class="img-fluid" src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>" />
		                                    </div>
						                <?php endif;?>
					                <?php endif;?>
		                            <div class="card-body">
		                                <h4 class="card-title display-5"><?php echo $title ;?></h4>
			                            <?php if (get_sub_field( 'content_length' ) == 'excerpt' ) : ?>
		                                    <?php echo snf_custom_excerpt(get_sub_field('content'));?>
			                            <?php endif;?>
			                            <?php if (get_sub_field( 'content_length' ) == 'full-length' ) : ?>
		                                    <?php the_sub_field('content');?>
			                            <?php endif;?>
		                            </div>
		                            <div class="card-footer bg-transparent border-0">
			                            <?php $button_link = get_sub_field( 'button_link' ); ?>
			                            <?php if ( $button_link ) : ?>
		                                   
                                            <?php if(get_sub_field('button_text')):?>
                                                <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link"><?php the_sub_field('button_text');?></a>
                                            <?php else:?>
                                                <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link">Read More</a>
                                            <?php endif;?>
		                                       
		                                <?php else:?>
			                            <?php endif; ?>
		                            </div>
		                        </div><!--card cta_card_slider-->
		                    </div><!--carousel-item col-md-4-->
		                <?php $i++;?>
						<?php endwhile; wp_reset_postdata(); ?>
					<?php endif;?>
		        </div><!--carousel-inner-->
		    </div><!--col-->
		</div><!--row-->
	<?php endif; ?>
<?php else : ?>
	<?php if (get_sub_field( 'card_layout' ) == 'traditional-card' ) : ?>
        <div class="row  row-cols-1 row-cols-md-3 flexible-card mb-5 mt-2">
            <?php if ( have_rows( 'card_repeater' ) ) : ?>
	            <?php while ( have_rows( 'card_repeater' ) ) : the_row(); ?>
	                <div class="col">
	                    <article class="card h-100">
				            <?php if (get_sub_field( 'image_type' ) == 'background-image' ) : ?>
					            <?php $image = get_sub_field('image');?>
                                <?php $title = get_sub_field('title');?>
                                <div class="traditional-card" style="background-image: url(<?php echo $image['url']; ?>)"></div><!--traditional-card-->
                            <?php else:?>
                                <?php if (get_sub_field( 'image_type' ) == 'responsive-image' ) : ?>
						            <?php $image = get_sub_field('image');?>
                                        <div class="card-img-top">
                                            <img class="img-fluid" src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>" />
                                        </div><!--card-img-top-->
                                <?php endif;?>
                            <?php endif;?>
	                        <div class="card-body text">
                                <h4 class="card-title display-5"><?php echo $title ;?></h4>
                                <?php if (get_sub_field( 'content_length' ) == 'excerpt' ) : ?>
                                    <?php echo snf_custom_excerpt(get_sub_field('content'));?>
                                <?php endif;?>
                                <?php if (get_sub_field( 'content_length' ) == 'full-length' ) : ?>
                                	<?php the_sub_field('content');?>
                                <?php endif;?>
	                        </div><!--card-body-->
                            <section class=" card-footer bg-transparency">
			                    <?php $button_link = get_sub_field( 'button_link' ); ?>
			                    <?php if ( $button_link ) : ?>

						                    <?php if(get_sub_field('button_text')):?>
                                                <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link"><?php the_sub_field('button_text');?> <i class="bi bi-chevron-double-right"></i></a>
						                    <?php else:?>
                                                <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link">Read More <i class="bi bi-chevron-double-right"></i></a>
						                    <?php endif;?>

			                    <?php endif; ?>
                            </section><!--card-footer-->
	                        <div class="shape__shadow"></div><!--shape__shadow-->
	                    </article>
	                </div><!--col-->
	            <?php endwhile; ?>
			<?php else : ?>
			<?php endif; ?>
        </div><!--flexible-card-->
    <?php endif; ?>
    <!--end of traditional vertical card layout -->
    <?php $counter = 0;  // check if the flexible content field has rows of data ?>
    <?php if (get_sub_field( 'card_layout' ) == 'horizontal-card' ) : ?>
    	<div class="row flexible-card mb-5 mt-2">
            <?php if ( have_rows( 'card_repeater' ) ) : ?>
                <section class="col-sm-12 light">
                    <?php while ( have_rows( 'card_repeater' ) ) : the_row(); ?>
                        <?php if ($counter % 2 === 0) :?>
                            <article class="postcard light blue">
                                <?php $button_link = get_sub_field( 'button_link' ); ?>
                                <?php if ( $button_link ) : ?>
                                    <a class="postcard__img_link" href="<?php echo esc_url( $button_link); ?>">
                                        <?php $image = get_sub_field('image');?>
                                        <?php if (get_sub_field( 'image_type' ) == 'background-image' ) : ?>
			                                <div class="horizontal_card_img" style="background-image: url(<?php echo $image['url']; ?>)"></div>
										<?php else:?>
							                <?php if (get_sub_field( 'image_type' ) == 'responsive-image' ) : ?>
													<img class="   postcard__img" src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>">
							                <?php endif;?>
						                <?php endif;?>
                                    </a>
                                <?php else:?>
	                                <?php $image = get_sub_field('image');?>
	                                <?php if (get_sub_field( 'image_type' ) == 'background-image' ) : ?>
		                                <div class="horizontal_card_img " style="background-image: url(<?php echo $image['url']; ?>)"></div><!--horizontal_card_img-->
									<?php else:?>
						                <?php if (get_sub_field( 'image_type' ) == 'responsive-image' ) : ?>
											<img class="postcard__img" src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>" />
						                <?php endif;?>
					                <?php endif;?>
                                <?php endif; ?>
                                <div class="postcard__text t-dark">
                                	<?php $title = get_sub_field('title');?>
                                    <h2 class="postcard__title blue"><?php echo $title ;?> </h2>
                                    <div class="postcard__bar"></div><!--postcard__bar-->
                                    <div class="postcard__preview-txt">
										<?php if (get_sub_field( 'content_length' ) == 'excerpt' ) : ?>
		                                    <?php echo snf_custom_excerpt(get_sub_field('content'));?>
			                            <?php endif;?>
			                            <?php if (get_sub_field( 'content_length' ) == 'full-length' ) : ?>
		                                    <?php the_sub_field('content');?>
			                            <?php endif;?>                                    
			                        </div><!--postcard__preview-txt-->
                                    <div class="postcard__tagbox">
	                                    <?php $button_link = get_sub_field( 'button_link' ); ?>
	                                    <?php if ( $button_link ) : ?>

                                            <?php if(get_sub_field('button_text')):?>
                                                <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link"><?php the_sub_field('button_text');?> <i class="bi bi-chevron-double-right"></i></a>
                                            <?php else:?>
                                                <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link">Read More <i class="bi bi-chevron-double-right"></i></a>
                                            <?php endif;?>

	                                    <?php endif; ?>
                                    </div><!--postcard__tagbox-->
                                </div><!--postcard__text-->
                            </article>
                        <?php else:?>
                            <article class="postcard light blue">
                                <?php $button_link = get_sub_field( 'button_link' ); ?>
                                <?php if ( $button_link ) : ?>
                                    <a class="postcard__img_link" href="<?php echo esc_url( $button_link); ?>">
                                        <?php $image = get_sub_field('image');?>
                                        <?php if (get_sub_field( 'image_type' ) == 'background-image' ) : ?>
			                                <div class="horizontal_card_img" style="background-image: url(<?php echo $image['url']; ?>)"></div><!--horizontal_card_img-->
										<?php else:?>
							                <?php if (get_sub_field( 'image_type' ) == 'responsive-image' ) : ?>
												<img class=" postcard__img" src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>" />
							                <?php endif;?>
						                <?php endif;?>
                                    </a>
                                <?php else:?>
                            		<?php $image = get_sub_field('image');?>
                                    <?php if (get_sub_field( 'image_type' ) == 'background-image' ) : ?>
		                                <div class="horizontal_card_img" style="background-image: url(<?php echo $image['url']; ?>)"></div><!--horizontal_card_img-->
									<?php else:?>
						                <?php if (get_sub_field( 'image_type' ) == 'responsive-image' ) : ?>
											<img class=" postcard__img" src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>" />
						                <?php endif;?>
					                <?php endif;?>
                                <?php endif; ?>
                                <div class="postcard__text t-dark">
                                	<?php $title = get_sub_field('title');?>
                                    <h2 class="postcard__title blue"><?php echo $title ;?> </h2>
                                    <div class="postcard__bar"></div><!--postcard__bar-->
                                    <div class="postcard__preview-txt">
	                                    <?php if (get_sub_field( 'content_length' ) == 'excerpt' ) : ?>
		                                    <?php echo snf_custom_excerpt(get_sub_field('content'));?>
	                                    <?php endif;?>
	                                    <?php if (get_sub_field( 'content_length' ) == 'full-length' ) : ?>
		                                    <?php the_sub_field('content');?>
	                                    <?php endif;?>
                                    </div><!--postcard__preview-txt-->
                                    <div class="postcard__tagbox">
	                                    <?php $button_link = get_sub_field( 'button_link' ); ?>
	                                    <?php if ( $button_link ) : ?>

				                                    <?php if(get_sub_field('button_text')):?>
                                                        <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link"><?php the_sub_field('button_text');?> <i class="bi bi-chevron-double-right"></i></a>
				                                    <?php else:?>
                                                        <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link">Read More <i class="bi bi-chevron-double-right"></i></a>
				                                    <?php endif;?>

	                                    <?php endif; ?>
                                    </div><!--postcard__tagbox-->
                                </div><!--postcard__text-->
                            </article>
                        <?php endif; ?>
                        <?php $counter++; ?>
                    <?php endwhile; ?>
                </section>
            <?php else : ?>
                <?php // No rows found ?>
            <?php endif; ?>
		</div><!--flexible-card-->
    <?php endif; ?>
<?php endif; ?>