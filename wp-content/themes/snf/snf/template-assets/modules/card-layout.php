<div class="row ">
    <?php if(get_sub_field('card_layout_title')):?>
        <div class="col-sm-12 card-title-identify">
            <h2 class="layout-title"><?php the_sub_field( 'card_layout_title' ); ?></h2>
        </div>
    <?php endif; ?>
    <?php if(get_sub_field('card_description')):?>
        <div class="col-sm-12 h-100 certificate-info card-description-identify">
            <?php the_sub_field( 'card_description' ); ?>
        </div>
    <?php endif; ?>
</div>
<?php if (get_sub_field( 'card_layout' ) == 'card-slider' ) : ?>
    <?php $counter = 0; $sliderCount = 0; ?>
    <div class="row repeater-slider">
        <section class="slide-wrapper">
            <div class="markets containerSlider">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="markets carousel-inner">
                         <?php if ( have_rows( 'card_repeater' ) ) : ?>
                                <?php $counter = 0;?>
                                <?php while ( have_rows('card_repeater') ) : the_row();?>
                                    <?php $title = get_sub_field('title');?>
                                    <div class="carousel-item <?php if ($counter === 0):?> active <?php endif;?>">
                                        <div class="fill row" >
                                            <?php $image = get_sub_field('image');?>
                                            <?php if($image):?>
                                                <div class="fillImage col-lg-6 col-sm-12 border" style="background-image: url(<?php echo $image['url']; ?>);">
                                                    <div class="design-overlay">
                                                        <h2><?php echo $title ;?></h2>
                                                    </div>
                                                </div>
                                            <?php endif;?>
                                            <div class="fillContent col-lg-6 col-sm-12">
                                                <?php $button_link = get_sub_field( 'button_link' ); ?>
                                                <p><?php echo snf_custom_excerpt(get_sub_field('content'));?><br></p>
                                                <?php   // Get terms for post
                                                $terms = get_the_terms( $post->ID , 'markets' );
                                                // Loop over each item since it's an array
                                                foreach ( $terms as $term ) {?>
                                                    <?php $termlinks = get_term_link($term);?>
                                                    <a href="<?php echo $termlinks ;?>" class="btn btn-outline-primary markets">
                                                        <?php echo $term->name;?>
                                                    </a>
                                                <?php }
                                                ?>
                                                <?php if ( $button_link ) : ?>
                                                    <a class="btn btn-outline-primary" href="<?php echo esc_url( $button_link); ?>">
                                                        Learn More
                                                    </a>
                                                <?php else:?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php $counter++; ?>
                            <?php endwhile; wp_reset_postdata(); ?>
                        <?php endif;?>
                    </div><!--carousel-inner-->
                    <div class="col-sm-12">
                        <ol class="carousel-indicators">
                             <?php if ( have_rows( 'card_repeater' ) ) : ?>
                                <?php $counter = 0;?>
                                <?php while ( have_rows('card_repeater') ) : the_row();?>
                                    <!-- Indicators -->
                                    <li data-target="#myCarousel" data-slide-to="<?php echo $counter ?>" class=" <?php if ($counter === 0):?> active <?php endif;?>"></li>
                                    <?php $counter++; ?>
                                <?php endwhile;  ?>
                            <?php endif; wp_reset_postdata();?>
                        </ol><!--carousel-indicators-->
                    </div>
                </div><!--myCarousel slide -->
            </div><!--containerSlider-->
        </section><!--slide-wrapper-->
    </div><!--repeater-slider -->
<?php else : ?>
	<?php if (get_sub_field( 'card_layout' ) == 'traditional-card' ) : ?>
        <div class="row  flexible-card mb-5 mt-2">
            <?php if ( have_rows( 'card_repeater' ) ) : ?>
	            <?php while ( have_rows( 'card_repeater' ) ) : the_row(); ?>
	                <div class="col ">
	                    <article class="card h-100">
				            <?php if (get_sub_field( 'image_type' ) == 'background-image' ) : ?>
					            <?php $image = get_sub_field('image');?>
                                <div class="traditional-card" style="background-image: url(<?php echo $image['url']; ?>)">
                                    <div class="card-tag">
							            <?php $title = get_sub_field('title');?>
							            <?php  $term_array = array();
							            $term_list = wp_get_post_terms($post->ID, 'markets', array(
									            "fields" => "all",
									            'orderby' => 'parent',
									            'order' => 'ASC'
								            )
							            );
							            foreach($term_list as $term_single) {
								            $term_array[] = $term_single->name ; //do something here
							            }
							            ?>
							            <?php foreach($term_list as $term) :?>
								            <?php $termlinks = get_term_link($term);?>
                                            <a href="<?php echo $termlinks ;?>">
                                                <h4 class="badge badge-light">
										            <?php echo $term->name;?>
                                                </h4>
                                            </a>
							            <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else:?>
                                <?php if (get_sub_field( 'image_type' ) == 'responsive-image' ) : ?>
						            <?php $image = get_sub_field('image');?>
                                        <div class="card-img-top">
                                            <img class="img-fluid" src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>" />

                                        </div>
                                <?php endif;?>
                            <?php endif;?>

	                        <div class="card-body text">
                                <h4 class="card-title display-5"><?php echo $title ;?></h4>
                                <?php if (get_sub_field( 'content_length' ) == 'excerpt' ) : ?>
                                    <p><?php echo snf_custom_excerpt(get_sub_field('content'));?></p>
                                <?php endif;?>
                                <?php if (get_sub_field( 'content_length' ) == 'full-length' ) : ?>
                                	<p><?php the_sub_field('content');?></p>
                                <?php endif;?>
	                        </div>
                            <section class=" card-footer bg-transparency">
			                    <?php $button_link = get_sub_field( 'button_link' ); ?>
			                    <?php if ( $button_link ) : ?>
                                    <div class="snf-link-wrapper ">
                                        <div class="snf-link">
						                    <?php if(get_sub_field('button_text')):?>
                                                <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link"><?php the_sub_field('button_text');?></a>
						                    <?php else:?>
                                                <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link">Read More</a>
						                    <?php endif;?>
                                        </div>
                                    </div>
			                    <?php endif; ?>
                            </section>
	                        <div class="shape__shadow"></div>
	                    </article>
	                </div><!--col-->
	            <?php endwhile; ?>
			<?php else : ?>
					<?php // No rows found ?>
			<?php endif; ?>
        </div><!--flexible-card-->
    <?php endif; ?>
    <!--end of traditional vertical card layout -->
    <?php $counter = 0;  // check if the flexible content field has rows of data ?>
    <?php if (get_sub_field( 'card_layout' ) == 'horizontal-card' ) : ?>
    	<div class="flexible-card">
            <?php if ( have_rows( 'card_repeater' ) ) : ?>
                <section class="col-sm-12 light">
                    <?php while ( have_rows( 'card_repeater' ) ) : the_row(); ?>
                        <?php if ($counter % 2 === 0) :?>
                            <article class="postcard light blue">
                                <?php $button_link = get_sub_field( 'button_link' ); ?>
                                <?php if ( $button_link ) : ?>
                                    <a class="postcard__img_link" href="<?php echo esc_url( $button_link); ?>">
                                        <?php $image = get_sub_field('image');?>
                                        <img class=" postcard__img" src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>">
                                    </a>
                                <?php else:?>
                                        <?php $image = get_sub_field('image');?>
                                        <img class=" postcard__img" src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>">
                                <?php endif; ?>
                                <div class="postcard__text t-dark">
                                      <?php $title = get_sub_field('title');?>
                                        <h2 class="postcard__title blue"><?php echo $title ;?> </h2>
                                        <div class="postcard__subtitle small">
                                            <time datetime="2020-05-25 12:00:00">
                                                <i class="fas fa-calendar-alt mr-2"></i>Mon, May 25th 2020
                                            </time>
                                        </div>
                                    <div class="postcard__bar"></div>
                                    <div class="postcard__preview-txt">
                                        <p><?php echo snf_custom_excerpt(get_sub_field('content'));?></p>
                                    </div>
                                    <div class="postcard__tagbox">
	                                    <?php $button_link = get_sub_field( 'button_link' ); ?>
	                                    <?php if ( $button_link ) : ?>
                                            <div class="snf-link-wrapper ">
                                                <div class="snf-link">
				                                    <?php if(get_sub_field('button_text')):?>
                                                        <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link"><?php the_sub_field('button_text');?>></a>
				                                    <?php else:?>
                                                        <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link">Read More</a>
				                                    <?php endif;?>
                                                </div>
                                            </div>
	                                    <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        <?php else:?>
                            <article class="postcard light blue">
                                <?php $button_link = get_sub_field( 'button_link' ); ?>
                                <?php if ( $button_link ) : ?>
                                    <a class="postcard__img_link" href="<?php echo esc_url( $button_link); ?>">
                                        <?php $image = get_sub_field('image');?>
                                        <img class=" postcard__img" src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>">
                                    </a>
                                <?php else:?>
                                        <?php $image = get_sub_field('image');?>
                                        <img class=" postcard__img" src="<?php echo $image['url']; ?>" alt="<?php echo $image['title']; ?>">
                                <?php endif; ?>
                                <div class="postcard__text t-dark">
                                      <?php $title = get_sub_field('title');?>
                                        <h2 class="postcard__title blue"><?php echo $title ;?> </h2>
                                        <div class="postcard__subtitle small">
                                            <time datetime="2020-05-25 12:00:00">
                                                <i class="fas fa-calendar-alt mr-2"></i>Mon, May 25th 2020
                                            </time>
                                        </div>
                                    <div class="postcard__bar"></div>
                                    <div class="postcard__preview-txt">
                                        <p><?php echo snf_custom_excerpt(get_sub_field('content'));?></p>
                                    </div>
                                    <div class="postcard__tagbox">
	                                    <?php $button_link = get_sub_field( 'button_link' ); ?>
	                                    <?php if ( $button_link ) : ?>
                                            <div class="snf-link-wrapper ">
                                                <div class="snf-link">
				                                    <?php if(get_sub_field('button_text')):?>
                                                        <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link"><?php the_sub_field('button_text');?>></a>
				                                    <?php else:?>
                                                        <a href="<?php echo esc_url( $button_link); ?>" class="product-list-link">Read More</a>
				                                    <?php endif;?>
                                                </div>
                                            </div>
	                                    <?php endif; ?>
                                    </div>
                                </div>
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