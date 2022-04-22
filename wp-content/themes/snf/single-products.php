<?php get_header();?>
<div class="container">
	<div class="row singleContent ">
		<div class="col-md-9 col-sm-12">
			<div class="row  mb-3">
                <?php
                if ( have_posts() ) :
                    while ( have_posts() ) : the_post(); ?>

                        <div class="col-md-12 col-sm-12 col-xs-12 mb-2 mt-2  products-grouping  ">
                            <div class="card h-100 shadow-sm product-card">
                                <div class="card-header bg-transparent product-identifier">
                                    <div class="col col-xs-12 product-title ">
					                    <?php if(get_field('trade_name')):?>
                                            <h3><?php the_field('trade_name'); ?> <?php if(get_field('product_range')):?> <?php the_field('product_range');?> <?php endif;?></h3>
					                    <?php else:?>
                                            <h3><?php  echo get_the_title(); ?> </h3>
					                    <?php endif;?>
                                    </div>

                                    <div class="col col-xs-12 market-badge ">
					                    <?php if(has_term('italy', 'country')):?>
						                    <?php   // Get terms for post
						                    $countryTerms = get_the_terms( $post->ID , 'country' );
						                    // Loop over each item since it's an array
						                    foreach ( $countryTerms as $c_term ) :?>

							                    <?php $termlinks = get_term_link($c_term);?>

                                                <a class="badge badge-primary  markets"   href="<?php echo $termlinks ['url'] ;?>">
								                    <?php echo $c_term->name;?>
                                                </a>

						                    <?php endforeach; ?>
					                    <?php endif;?>
					                    <?php   // Get terms for post
					                    $terms = get_the_terms( $post->ID , 'markets' );
					                    // Loop over each item since it's an array
					                    foreach ( $terms as $term ) {?>
						                    <?php $marketColor = get_field('color_scheme', 'category_'.$term->term_id); ?>
						                    <?php $marketRedirect = get_field('page_url', 'category_'.$term->term_id); ?>
						                    <?php $termlinks = get_term_link($term);?>
						                    <?php if($marketRedirect){?>
                                                <a class="badge badge-primary bg-transparent markets" style="border:solid 1px <?php echo $marketColor;?>; color:<?php echo $marketColor;?>" href="<?php echo $marketRedirect ;?>">
								                    <?php echo $term->name;?>
                                                </a>
						                    <?php }else{?>
                                                <a class="badge badge-primary bg-transparent markets" style="border:solid 1px <?php echo $marketColor;?>; color:<?php echo $marketColor;?>" href="<?php echo $termlinks ['url'] ;?>">
								                    <?php echo $term->name;?>
                                                </a>
						                    <?php }?>
					                    <?php }
					                    ?>
                                    </div><!--market-badge-->
                                </div><!--card-header-->
                                <div class="row no-gutters border-bottom">
                                    <div class="col-md-6  product-tag  text-center border-right" >
                                        <h4 class=" panel-title prod-cat mb-0 " style="background-color:#0082CA">
                                            <a class="lead product-misc-cat text-white" data-toggle="collapse" href="#categoryProduct_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>_cat" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                Category / Type
                                            </a>
                                        </h4>
                                        <div class="collapse" id="categoryProduct_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>_cat">
						                    <?php  $term_array = array();
						                    $term_list = wp_get_post_terms($post->ID, 'products-category', array(
								                    "fields" => "all",
								                    'orderby' => 'parent',
								                    'order' => 'ASC'
							                    )
						                    );
						                    foreach($term_list as $term_single) {
							                    $term_array[] = $term_single->name ; //do something here
						                    }
						                    ?>
                                            <div class="card-body px-2">
							                    <?php foreach($term_list as $term) :?>
                                                    <p class="lead "><?php echo $term->name;  ?></p>
							                    <?php endforeach;?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6  product-tag  text-center  border-left" >
                                        <h4 class=" panel-title prod-cat  mb-0" style="background-color:rgb(107,105,135);">
                                            <a class="lead product-misc-cat text-white" data-toggle="collapse" href="#applicationProduct_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>_app" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                Applications / Uses
                                            </a>
                                        </h4>
                                        <div class="collapse bg-transparent" id="applicationProduct_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>_app">
						                    <?php  $term_array = array();
						                    $term_list = wp_get_post_terms($post->ID, 'product-applications', array(
								                    "fields" => "all",
								                    'orderby' => 'parent',
								                    'order' => 'ASC'
							                    )
						                    );
						                    foreach($term_list as $term_single) {
							                    $term_array[] = $term_single->name ; //do something here
						                    }
						                    ?>
                                            <div class="card-body px-2">
							                    <?php foreach($term_list as $term) :?>

                                                    <p class="lead depth_<?php echo  $accord_count++;?>"><?php echo $term->name;  ?></p>
							                    <?php endforeach;?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">

				                    <?php if(get_field('descriptionuses')):?>
                                        <div class="col">
                                            <p class="lead"><?php the_field('descriptionuses');?></p>
                                        </div>
				                    <?php endif;?>
                                </div><!--card-body description-->
                                <div class="card-footer bg-transparent ">
				                    <?php $accord_count = 0; $Acc_ID = uniqid();  $row_count = 0; $accord_count++;?>
                                    <h4 class=" panel-title ">
                                        <a class="lead product-accordion btn btn-block" data-toggle="collapse" href="#collapseProduct_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            More Product Information
                                        </a>
                                    </h4>
                                    <div class="collapse" id="collapseProduct_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>">
                                        <div class="card-body">

                                            <div class="row product-contact  pt-3">
                                                <div class=" col-xs-12  product-detail-cont mb-2">
								                    <?php if(get_field('inci_name')):?>
                                                        <div class="col d-block">
                                                            <b>INCI NAME:</b>
                                                        </div>
                                                        <div class="col">
                                                            <p class="lead"><?php the_field('inci_name');?></p>
                                                        </div>
								                    <?php endif;?>
								                    <?php if(get_field('%_active')):?>
                                                        <div class="col d-block">
                                                            <b>% Active:</b>
                                                        </div>
                                                        <div class="col d-block">
                                                            <p class="lead"><?php the_field('%_active');?></p>
                                                        </div>
								                    <?php endif;?>
								                    <?php if(get_field('recommnded_dosage_&_ph_range')):?>
                                                        <div class="col d-block">
                                                            <b>Recommended Dosage & PH Range:</b>
                                                        </div>
                                                        <div class="col">
                                                            <p class="lead"><?php the_field('recommnded_dosage_&_ph_range');?></p>
                                                        </div>
								                    <?php endif;?>
                                                </div>
                                            </div>
                                            <div class="row product-contact border-top pt-3">
                                                <div class="col prod_button_redirect">
                                                    <a href="<?php echo home_url('/');?>contact/" class="" >
                                                        <button type="button" class="btn btn-outline-primary" style="background-color:rgb(107,105,135); color:#fff; border:none;">
                                                            <span>Contact Sales</span> <i class="bi bi-chevron-right"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                                <div class="col prod_button_redirect">
								                    <?php $terms = get_the_terms(get_the_ID(), 'markets');?>

								                    <?php foreach($terms as $term) :?>
                                                        <a href="<?php echo home_url('/');?>sds-request/?tradename=<?php the_field('trade_name');?> <?php the_field('product_range');?>&industry=<?php echo $term->name;  ?>" class="" >
                                                            <button type="button" class="btn btn-outline-secondary" style="background-color:#0082CA; color:#FFF;border:none; ">
                                                                <span>Request SDS</span> <i class="bi bi-chevron-right"></i>
                                                            </button>
                                                        </a>
								                    <?php endforeach;?>
                                                </div>
                                            </div>
                                        </div><!--card-body-->
                                    </div>
                                </div><!--card-footer-->
                            </div>
                        </div>


                    <?php endwhile;
                endif;
                ?>



				<div class="text-center col-sm-12 mb-3 mt-3">
					<a class="btn btn-sm btn-outline-primary" href="<?php echo home_url('/');?>group-products">Back to All Products</a>
				</div>
				<section class="contact-bar col-sm-12 bg-fixed text-white bg-dark" style="background-image: url(<?php bloginfo('template_directory'); ?>/resources/images/CraterLakeHeroImage-scaled.jpg)">
					<div class="section-header">
						<div class="site-cta-container">
							<div class="text-center">
								<h2>Contact SNF Today!</h2>
							</div>
							<hr>
							<div class="text-center">
								<a class="btn btn-outline-light text-white" href="<?php echo home_url('/') ; ?>contact">Lets Work Together</a>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
		<div class="col-md-3 col-sm-12 articlesSideBar border-left">
			<aside class=" list-group newsSideBar">
				<section class="sidebar-widget">
					<?php $terms = get_the_terms(get_the_ID(), 'product-applications');?>

                    <h3 class="sidebarTitle pb-2">Similar Products:</h3>
					<?php foreach( $terms as $term ): ?>
					<?php
					// the query
					$the_query = new WP_Query( array(
						'post_type' =>  'products',
						'posts_per_page' => 3,
						'tax_query' => array(
							array(
								'taxonomy' => 'markets',
								'field' => 'slug',
								'terms' => $term->slug,
							),
						),
					));
					?>
					<ul>
						<?php if ( $the_query->have_posts() ) : ?>
							<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
								<li class="list-group-item">
									<a href="<?php the_permalink();?>">
										<h4 class="recentPosts"><?php the_field('trade_name');?> <?php if(get_field('product_range')):?><?php the_field('product_range');?><?php endif;?></h4>
									</a>
								</li>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
						<?php else : ?>
							<p><?php __('No Products'); ?></p>
						<?php endif; ?>

					</ul>
					<?php endforeach; ?>
				</section>
				<section class="sidebar-widget">
					<h3 class="sidebarTitle">Categories</h3>
					<div class="col-xs-12 card-body">
						<div class="tag-cloud">
							<i class="fa fa-tags" aria-hidden="true"></i>
							<?php   // Get terms for post
							$terms = get_the_terms( $post->ID , 'snf-communication-types' );
							// Loop over each item since it's an array
							foreach ( $terms as $term ) {?>
								<?php $termlinks = get_term_link($term);?>
								<a href="<?php echo $termlinks ;?>" class="badge badge-tag comm-types">
									<?php echo $term->name;?>
								</a>
							<?php }
							?>
							<?php   // Get terms for post
							$terms = get_the_terms( $post->ID , 'markets' );
							// Loop over each item since it's an array
							foreach ( $terms as $term ) {?>
								<?php $termlinks = get_term_link($term);?>
								<a href="<?php echo $termlinks ;?>" class="badge badge-tag markets">
									<?php echo $term->name;?>
								</a>
							<?php }
							?>

						</div><!--tag-cloud-->
				</section>

			</aside><!--newsSideBar-->
		</div><!--articlesSideBar-->
	</div>

</div>


<?php get_footer();?>
