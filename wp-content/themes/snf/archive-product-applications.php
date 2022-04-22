<?php

get_header();?>
<div class="container ">
	<div class="row mt-3 mb-3">
		<div class="col-sm-12">
			<?php
                $args = array(
                    'post_type' =>  'products',
                    'posts_per_page' => -1,
                    'order' => 'DESC',
                    'orderby' => 'title',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product-applications',
                            'field' => 'slug',
                            'terms' => end( ( explode( '/', rtrim( $_SERVER['REQUEST_URI'], '/' ) ) ) )
                        )
                    )

                );
                $query = new WP_Query( $args );
                ?>
                <?php if ( $query->have_posts() ) : $i = 0;?>
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
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
                                            <div class="col-xs-12  product-tag mb-2 ">
                                                <h4 class=" panel-title prod-cat">
                                                    <a class="lead product-misc-cat" data-toggle="collapse" href="#categoryProduct_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>_cat" role="button" aria-expanded="false" aria-controls="collapseExample">
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
                                                            <p class="lead"><?php echo $term->name;  ?></p>
                                                        <?php endforeach;?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class=" col-xs-12  product-tag mb-2">
                                                <h4 class=" panel-title prod-cat">
                                                    <a class="lead product-misc-cat " data-toggle="collapse" href="#applicationProduct_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>_app" role="button" aria-expanded="false" aria-controls="collapseExample">
                                                        Applications / Uses
                                                    </a>
                                                </h4>
                                                <div class="collapse" id="applicationProduct_<?php echo get_row_index(); ?>_<?php echo $Acc_ID; ?>_app">
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
                                        </div><!--card-body-->
                                    </div>
                                </div><!--card-footer-->
                            </div>
                        </div>
                <?php endwhile; wp_reset_postdata();?>
            <?php endif;?>
		</div>
	</div>
    
<?php get_footer();?>