 <div class="container-fluid">
	<div class="row mt-3 ">
		<div class="col-sm-12 infinity_industry_loop ">
            <section class="market_box ">
				<?php
		        $term_list = get_terms( array(
		            'taxonomy' => 'markets',
		            'hide_empty' => 'false',
		            'orderby' => 'meta_value_num',
		            'meta_key' => 'order_number',
		            "fields" => "all",
		        ) );?>
		        <?php $count = 0;?>
		        <ul class="h-100">
				  	<?php foreach($term_list as $term_single): $count ++; ?>
		            	<?php if($term_single->parent > 0):?>
							<?php $marketColorSelection = get_field('color_scheme', 'category_'.$term_single->term_id); ?>
							<?php $cat_image = get_field('hero_image', 'category_'.$term_single->term_id); ?>
							<?php $marketIcon = get_field('market_icon', 'category_'.$term_single->term_id); ?>
							<?php $termLink = get_field('page_url', 'category_'.$term_single->term_id); ?>
							<?php if ( $termLink ) : ?>
	                            <li class=" industry_list_element market_<?php echo $term_single->slug;?>  ">
	                                <a href="<?php echo esc_url( $termLink) ; ?>" class="">
		                                <?php if ( $cat_image ) : ?>
			                            	<div class="scroll-container">
				                            	<img src="<?php echo esc_url( $cat_image['url'] ); ?>" alt="<?php echo esc_attr( $cat_image['alt'] ); ?>" class="img-fluid" />
			                            	</div>
		                            	<?php endif; ?>
	                                    <div class="industry_loop_container loop_content">
		                                    <div class="market-title  h-100"style="background:<?php echo $marketColorSelection ;?>">
					                            <div class="icon-square-hover">
					                                <img src="<?php echo $marketIcon['url'];?>" class="img-fluid" />
					                            </div>
					                            <div class="card-container">
					                                <h3 class="card-title"><?php echo $term_single->name ?></h3>
					                            </div>
					                        </div><!--market-title-->
	                                    </div>
	                                </a>
	                            </li>
	                        <?php else:?>
	                            <li class=" industry_list_element market_<?php echo $term_single->slug;?> mt-3 mb-3 h-100">
	                            	<?php if ( $cat_image ) : ?>
		                            	<div class="scroll-container">
			                            	<img src="<?php echo esc_url( $cat_image['url'] ); ?>" alt="<?php echo esc_attr( $cat_image['alt'] ); ?>" class="img-fluid" />
		                            	</div>
		                            <?php endif; ?>
                                    <div class="industry_loop_container loop_content">
	                                    <div class="market-title  h-100"style="background:<?php echo $marketColorSelection ;?>">
				                            <div class="icon-square-hover">
				                                <img src="<?php echo $marketIcon['url'];?>" class="img-fluid" />
				                            </div>
				                            <div class="card-container">
				                                <h3 class="card-title"><?php echo $term_single->name ?></h3>
				                            </div>
				                        </div><!--market-title-->
                                    </div>
	                            </li>
						    <?php endif; ?>
						<?php endif;?>
			        <?php endforeach;?>
			    </ul>
            </section>
        </div>
    </div>
    <!-- ACCORDION ROW -->
</div>