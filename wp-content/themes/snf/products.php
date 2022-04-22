<?php
/**
 * Template Name:Products
 *

 */

get_header(); ?>
    <div class="container  ">
	    <div class="row">
		    	1st select market
	    </div>
		<div class="row ">
			<?php
            $terms = get_terms('markets' );
            if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {?>

                <?php foreach ($terms as $term) : ?>
                    <?php if ($term->parent != 0):?>
                        <?php $termLink = get_field('page_url', 'category_'.$term->term_id); ?>
                        <?php  $url = get_term_link($term->slug, 'markets');?>
                        <?php $cat_image = get_field('hero_image', 'category_'.$term->term_id); ?>

                        <div class="col-md-4 col-sm-12 mb-3 mt-3 user_market_selection" id="<?php the_ID(); ?>">
                            <div class="card h-100">
                                 <a href="<?php echo get_term_link($term->slug, 'markets');?>">
                                     <div class="card-header bg-transparent">
                                         <h2 ><?php echo $term->name?></h2>
                                     </div>
                                     <div class="market_background" style="background-image: url('<?php echo $cat_image['url']; ?>')">
                                         <div class="product_market_selection overlay"></div>
                                         <section class="card-body ">
                                        </section>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <h3>Explore this Market  </h3>
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
                        </div>
                    <?php endif;?>
               <?php endforeach;?>
            <?php }
            ?>
        </div>
        <div class="row mt-5 mb-5 text-center">
	        <h2><b>TWO DIFFERENT FILTERING LAYOUT OPTIONS / WORKING OUT BUGS AND FILTERING</b></h2>
	        
        </div>
        <div class="row ">
            <div class="col-md-3 col-xs-12 card card-filter shadow-sm" >
                <form class="card-body " >
                    <div class="form-row">
                        <div class="filter-title-box mb-3">
                            <h3 class="display-5">Filter</h3>
                        </div>
                        <?php echo do_shortcode('[fe_widget]'); ?>
                    </div>
                </form>
            </div>
            <div class="col-md-9 col-xs-12 products-grouping">
                <div class="row ">
                    <?php
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $temp =  $query;
                        $the_query = null;
                        $args = array(
                            'post_type' => 'products',
                            'posts_per_page' => '16',
                            'order' => 'ACS',
                            'orderby' => 'name',
                            'paged' => $paged,
                        );
                        $the_query = new WP_Query( $args ); ?>
                        <?php if ( $the_query->have_posts() ) :?>
                            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                            	<div class="col-md-6 col-sm-6 col-xs-12 mb-2 mt-2  ">
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
                            <?php endwhile; ?>
                            <?php if ($the_query->max_num_pages > 1) : // custom pagination  ?>
                                <?php
                                $orig_query = $wp_query; // fix for pagination to work
                                $wp_query = $the_query;
                                ?>
                                <div class="card-body">
                                    <div class=" col-sm-12 pagination pagination-wrapper">
                                        <?php echo bootstrap_pagination(); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php wp_reset_postdata();?>
                        <?php else:?>
                            <p>Sorry, there are no posts to display</p>
                        <?php endif; ?>
                </div>
                </div><!--card-deck-->
</div>
    </div>
<?php get_footer(); ?>