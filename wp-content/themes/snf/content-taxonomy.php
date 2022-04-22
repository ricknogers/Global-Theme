<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 mb-2 mt-2  taxonomy-template-prd-display">
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
                <?php if(get_field('descriptionuses')):?>
                    <div class="col-sm-12">
                        <p class="lead"><?php the_field('descriptionuses');?></p>
                    </div>
                <?php else:?>
	                <?php if ( have_rows( 'card_repeater' ) ) : ?>
		                <?php while ( have_rows( 'card_repeater' ) ) : the_row(); ?>
			                <?php if(snf_custom_excerpt(get_sub_field('content'))):?>
                                <p class="card-text  lead"><?php echo snf_custom_excerpt(get_sub_field('content'));?></p>

			                <?php endif;?>

		                <?php endwhile; wp_reset_postdata(); ?>
	                <?php endif;?>
	                <?php if(custom_field_excerpt()):?>
                        <p class="card-text  lead"><?php echo custom_field_excerpt();; ?></p>
	                <?php else:?>
                        <p class="card-text text-black-50 lead"><?php the_excerpt();; ?></p>
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
                            <a href="<?php echo home_url('/');?>contact/?contact_sales=" class="" >
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
                <?php endif;?>



            </div><!--card-footer-->
        </div>
    </div>
</div>