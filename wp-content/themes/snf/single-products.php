f<?php get_header();?>
<div class="container">
	<div class="row singleContent ">
		<div class="col-md-9 col-sm-12">
			<div class="row  mb-3">
                <?php if ( have_posts() ) :?>
                    <?php while ( have_posts() ) : the_post(); ?>
                        <div class="col-md-12 col-sm-12 col-xs-12 mb-2 mt-2 products-grouping  ">
                            <div class="card h-100 shadow-sm product-card">
                                <div class="card-header bg-transparent product-identifier">
                                    <div class="col col-xs-12product-title ">
				                        <?php if(get_field('trade_name')):?>
                                            <h3 style="color:#0082CA;"><?php the_field('trade_name'); ?> <?php if(get_field('product_range')):?> <?php the_field('product_range');?> <?php endif;?></h3>
				                        <?php else:?>
                                            <h3 style="color:#0082CA;"><?php  echo get_the_title(); ?> </h3>
				                        <?php endif;?>
                                    </div><!--product-title-->
                                    <div class="col col-xs-12 market-badge ">
		                                <?php if(has_term('italy', 'country')):?>
			                                <?php   // Get terms for post
			                                $countryTerms = get_the_terms( $post->ID , 'country' );
			                                // Loop over each item since it's an array
			                                foreach ( $countryTerms as $c_term ) :?>
				                                <?php $termlinks = get_term_link($c_term->slug, 'country');?>
                                                <?php $link = get_field('country_page_redirect', 'category_'.$c_term->term_id); ?>
                                                <?php if($link):?>
                                                    <a class="badge badge-primary  markets" href="<?php echo $link['url'];?>">
                                                        <?php echo $c_term->name;?>
                                                    </a>
                                                <?php else:?>
                                                    <a class="badge badge-primary  markets"   href="<?php echo $termlinks ['url'] ;?>">
						                                <?php echo $c_term->name;?>
                                                    </a>
				                                <?php endif;?>
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
                                <div class="card-body">
	                                <?php $terms = get_the_terms(get_the_ID(), 'country');?>
			                        <?php if($terms == 'italy' ):?>
                                        <div class="col-sm-12">
                                            <p class="lead"><?php the_content();?></p>
                                        </div>
			                        <?php else:?>
                                        <?php if(get_field('descriptionuses')):?>
                                            <div class="col-sm-12">
                                                <p class="lead"><?php the_field('descriptionuses');?></p>
                                            </div>
				                        <?php endif;?>
			                        <?php endif;?>
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
			                        <?php if($term_list != NULL):?>
                                        <div class="col-sm-12 product_terms ">
                                            <h4 class="display-5 mb-0 pr-3">Category : </h4>
					                        <?php foreach($term_list as $term) :?>
                                                <p class="lead mb-0"><?php echo $term->name;  ?></p>
					                        <?php endforeach;?>
                                        </div>
			                        <?php endif;?>
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
			                        <?php if($term_list != NULL):?>
                                        <div class="col-sm-12 product_terms ">
                                            <h4 class="display-5 mb-0 pr-3 ">Application / Usage : </h4>
					                        <?php foreach($term_list as $term) :?>
						                        <?php if($term->parent != 0):?>
                                                    <p class="mb-0 lead depth_<?php echo  $accord_count++;?>"><?php echo $term->name;  ?></p>
						                        <?php endif;?>
					                        <?php endforeach;?>
                                        </div>
			                        <?php endif;?>
                                </div><!--card-body description-->
                                <div class="card-footer bg-transparent ">
			                        <?php if(get_field('inci_name') || get_field('inci_name') || get_field('%_active') || get_field('recommnded_dosage_&_ph_range') || get_field('recommnded_dosage_&_ph_range')):?>
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
			                        <?php endif;?>
			                        <?php $post_type = get_post_type_object($post->post_type);?>
			                        <?php       $queriedTaxonomy = get_queried_object();?>
			                        <?php if (   get_post_type() =='global-communication'  )   : ?>
                                        <div class="float-right">
                                            <p class="text-sm-left text-md-right text-muted"><small><?php echo $post_type->labels->singular_name;?></small></p>
                                        </div>
			                        <?php else:?>
                                        <div class="row product-contact  ">
                                            <div class="col prod_button_redirect">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" style="border:solid 1px rgb(107,105,135); color:rgb(107,105,135);">
                                                        <a href="<?php echo home_url('/');?>contact/?contact_sales=" class="" >
                                                            <span> Contact Sales</span> <i class="bi bi-chevron-right"></i>
                                                        </a>
                                                    </button>
                                                    <?php $terms = get_the_terms(get_the_ID(), 'markets');?>
	                                                <?php foreach($terms as $term) :?>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" style="border solid 1px rgb(107,105,135); color:rgb(107,105,135);  ">
                                                            <a href="<?php echo home_url('/');?>sds-request/?tradename=<?php the_field('trade_name');?> <?php the_field('product_range');?>&industry=<?php echo $term->name;  ?>" class="" >
                                                                <span>Request SDS</span> <i class="bi bi-chevron-right"></i>
                                                            </a>
                                                        </button>
	                                                <?php endforeach;?>
                                                </div>
                                            </div>
                                        </div>
			                        <?php endif;?>
                                </div><!--card-footer-->
                            </div>
                        </div>
                    <?php endwhile;?>
                <?php endif;?>
				<div class="text-center col-sm-12 mb-3 mt-3">
					<a class="btn btn-sm btn-outline-primary" href="<?php echo home_url('/');?>group-products">Back to All Products</a>
				</div>
                <section class="contact-bar bg-fixed text-white " >
                    <div class="container p-0">
                        <div class="contact_bg_bar" style="background-image: url(<?php bloginfo('template_directory'); ?>/resources/images/CraterLakeHeroImage-scaled.jpg)">
                            <header class="section-header">
                                <div class="text-center">
                                    <h2>Contact SNF Today!</h2>
                                </div>
                                <hr class="diamond">
                                <div class="text-center">
                                    <a class=" text-white" href="<?php echo home_url('/') ; ?>contact">
                                        <button type="button" class="btn btn-outline-light">
                                            Lets Work Together
                                        </button>
                                    </a>
                                </div>
                            </header>
                        </div>
                    </div>
                </section>
			</div>
		</div>
		<div class="col-md-3 col-sm-12 articlesSideBar border-left">
			<aside class=" list-group newsSideBar">
				<div class="col-sm-12 p-0">
					<div class="card border-primary mb-3" >
						<div class="card-body sidebar-archive-contact " style="background-image: url(<?php bloginfo('template_directory'); ?>/resources/images/CraterLakeHeroImage-scaled.jpg)">
				            <div class="sidebar-overlay" style="background: linear-gradient(167deg, rgba(0,45,115,1) 0%, rgb(10,190,194) 51%);"></div>
				            <div class="markets-contact-content">
				                <h3>Contact SNF Today!</h3>
				                <a class="btn btn btn-outline-light" href="<?php echo home_url('/') ;?>contact">
				                    Let's Connect!
				                </a>
				            </div>
				        </div>
					</div>
				</div>
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
								'taxonomy' => 'product-applications',
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
