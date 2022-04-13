<?php 
get_header();?>
<div class="container ">
	<div class="row mt-3 mb-3 app-useage">
	<?php $applicationName = get_queried_object(); ;?>
	<div class="col-sm-12 d-block archive-header">
		<h1 class="archive-title"><?php echo $applicationName->name; ?></h1>
		<h3>Step Two: Select Application</h3>
	</div>
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
								<div class="col-md-4 col-sm-12 card-item mb-3 mt-3" id="<?php the_ID(); ?>">
									<div class="card shadow  h-100">	                              
									<?php $cat_image = get_field('image', 'category_'.$category->term_id); ?>
		                                    <div class="market_background" style="background-image: url('<?php echo $cat_image['url']; ?>')">
			                                    <?php $products = get_field( 'products', 'category_'.$category->term_id); ?>
			                                     <div class="product_market_selection overlay"></div>
			                                     <section class="card-body ">
				                         
				                                     <?php if ( $products ) : ?>
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

											
				                              				                            <div class="card-footer bg-transparent">
											 <a href="<?php echo get_term_link($category->slug, 'product-applications');?>">	                                            
												<h3 class="card-title" style="text-align: center"><?php echo $category->name ;?> </h3>
											</a>
				                            </div>                                
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