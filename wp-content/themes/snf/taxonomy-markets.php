<?php 
get_header();?>
<div class="container ">
	<div class="row mt-3 mb-3 app-useage">
	<?php $applicationName = get_queried_object(); ;?>

	<?php 
		// get the currently queried taxonomy term, for use later in the template file
		$applicationName = get_queried_object();

		$markets_post_IDs = get_posts(array(
			'post_type' => 'products',
			'posts_per_page' => -1,
			'tax_query' => array(
			array(
				'taxonomy' => 'markets',
				'field' => 'slug',
				'terms' => $applicationName->slug,
				)
			),
			'fields' => 'ids'
		));?>
		<div class="row equal">
			<?php // getting the terms of 'destination_category', which are assigned to these posts ?>
			<?php $prod_applications = wp_get_object_terms($markets_post_IDs, 'product-applications');?>
			<?php if ( ! is_wp_error( $prod_applications ) && ! empty( $prod_applications ) ) { ;?>
				<?php foreach( $prod_applications as $category ):?>
					<?php if ($category->parent != 0):?>
						<?php $args = array(
							'post_type' =>  'products',
							'posts_per_page' => 1,
							'order' => 'ASC',
							'orderby' => 'title',
							'tax_query' => array(
								array(
								'taxonomy' => $applicationName->taxonomy,
								'field' => 'slug',
								'terms' => $applicationName->slug,
								)
							)

						);
						$query = new WP_Query( $args );?>
						<?php  $url = get_term_link($category->slug, 'product-applications');?>
						<?php if ( $query->have_posts() ) : $i = 0;?>
							<?php while ( $query->have_posts() ) : $query->the_post(); ?> 
								<div class="col-md-4 col-sm-12 card-item mb-3 mt-3 user_market_selection" id="<?php the_ID(); ?>">
									<div class="card shadow  h-100">
                                        <a href="<?php echo get_term_link($category->slug, 'product-applications');?>">
                                            <div class="card-header bg-transparent tax-markets">
                                                <h2 ><?php echo $category->name?></h2>
                                            </div>
                                        </a>
                                        <?php $cat_image = get_field('image', 'category_'.$category->term_id); ?>
										<?php $products = get_field( 'products', 'category_'.$category->term_id); ?>

                                        <div class="market_background" style="background-image: url('<?php echo $cat_image['url']; ?>')">
                                            <div class="product_market_selection overlay"></div>
                                            <section class="card-body ">
	                                            <?php if ( $products ) : ?>
                                                    <h5 class=" border-bottom text-white">Featured Products</h5>
                                                    <ul class="list-style list-style-flush">
			                                            <?php foreach ( $products as $post ) : ?>
                                                            <li class="list-style-group">
					                                            <?php setup_postdata ( $post ); ?>
                                                                <a class="text-white" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                            </li>
			                                            <?php endforeach; ?>
			                                            <?php wp_reset_postdata(); ?>
                                                    </ul>
	                                            <?php endif; ?>
                                            </section>
                                        </div>

                                        <a href="<?php echo get_term_link($category->slug, 'product-applications');?>">
                                            <div class="card-footer bg-transparent">
                                                <h3>Explore this Application  </h3>
                                                <svg width="18px" height="17px" viewBox="-1 0 18 17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                    <g>
                                                        <polygon class="arrow" points="16.3746667 8.33860465 7.76133333 15.3067621 6.904 14.3175671 14.2906667 8.34246869 6.908 2.42790698 7.76 1.43613596"></polygon>
                                                        <polygon class="arrow-fixed" points="16.3746667 8.33860465 7.76133333 15.3067621 6.904 14.3175671 14.2906667 8.34246869 6.908 2.42790698 7.76 1.43613596"></polygon>
                                                        <path d="M-4.58892184e-16,0.56157424 L-4.58892184e-16,16.1929159 L9.708,8.33860465 L-1.64313008e-15,0.56157424 L-4.58892184e-16,0.56157424 Z M1.33333333,3.30246869 L7.62533333,8.34246869 L1.33333333,13.4327013 L1.33333333,3.30246869 L1.33333333,3.30246869 Z"></path>
                                                    </g>
                                                </svg>
                                            </div>
                                        </a>

									</div>
								</div><!--card-item-->
							<?php endwhile; wp_reset_postdata();?>
						<?php endif;?> 
					<?php endif;?>                        
				<?php endforeach;?>
			<?php } ?>
		</div><!--row equal -->
		</div>
	</div>
<?php get_footer();?>