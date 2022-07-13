<?php  $terms = get_the_terms( $post->ID , 'markets' );?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 mb-2 mt-2  taxonomy-template-prd-display">
        <div class="card h-100 shadow-sm product-card">
            <div class="card-header bg-transparent product-identifier">
                <div class="col col-xs-12 product-title ">
                    <?php if(get_field('trade_name')):?>
                        <h3 style="color:#002d73;"><?php the_field('trade_name'); ?> <?php if(get_field('product_range')):?> <?php the_field('product_range');?> <?php endif;?></h3>
                    <?php else:?>
                        <a href="<?php echo the_permalink();?>">
                            <h4 style="color:#002d73;" class="card-title"><?php  echo get_the_title(); ?> </h4>
                        </a>
                    <?php endif;?>
                </div><!--product-title-->

            </div><!--card-header-->
            <div class="card-body">
	            <?php if ( have_rows( 'card_repeater' ) ) : ?>
		            <?php while ( have_rows( 'card_repeater' ) ) : the_row(); ?>
			            <?php if(snf_custom_excerpt(get_sub_field('content'))):?>
				            <?php echo snf_custom_excerpt(get_sub_field('content'));?>
			            <?php endif;?>
		            <?php endwhile; wp_reset_postdata(); ?>
	            <?php endif;?>
	            <?php if(custom_field_excerpt()):?>
                    <p class="card-text text-black-50 lead"><?php echo custom_field_excerpt();; ?></p>
	            <?php else:?>
                    <p class="card-text text-black-50 lead"><?php the_excerpt();; ?></p>
	            <?php endif;?>
            </div>

            <div class="card-footer bg-transparent ">

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
</div>
