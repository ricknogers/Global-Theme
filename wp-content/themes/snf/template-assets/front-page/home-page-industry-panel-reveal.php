<div class="container">
    <div class="row mt-md-3 mb-md-3">
        <div class="col-sm-12 news-title-seperation">
            <section class="news_title_container">
                <div class="heading "><h4 class="display-3">SNF Industries</h4></div>
            </section>

        </div>
    </div>
</div>
<div class="container-fluid">

	<div class="row mt-3 ">
		<div class="marquee col-sm-12 shadow">
			<div class="marquee-content">
				<?php
		        $term_list = get_terms( array(
		            'taxonomy' => 'markets',
		            'hide_empty' => 'false',
		            'orderby' => 'meta_value_num',
		            'meta_key' => 'order_number',
		            "fields" => "all",
		        ) );?>
		        <?php $count = 0;?>

				<?php foreach($term_list as $term_single): $count ++; ?>
	            	<?php if($term_single->parent > 0):?>
						<?php $marketColorSelection = get_field('color_scheme', 'category_'.$term_single->term_id); ?>
						<?php $cat_image = get_field('hero_image', 'category_'.$term_single->term_id); ?>
						<?php $marketIcon = get_field('market_icon', 'category_'.$term_single->term_id); ?>
						<?php $termLink = get_field('page_url', 'category_'.$term_single->term_id); ?>
						<?php if ( $termLink ) : ?>
							<div class="marquee-tag marquee_element_<?php echo $term_single->slug;?>">
								 <a href="<?php echo esc_url( $termLink) ; ?>" class="">
									<?php if ( $cat_image ) : ?>
		                            
			                            	<img src="<?php echo esc_url( $cat_image['url'] ); ?>" alt="<?php echo esc_attr( $cat_image['alt'] ); ?>" class="img-fluid" loading="lazy" />
                                            <div class="element_title">
                                                <div class="market-title  h-100"style="background:<?php echo $marketColorSelection ;?>">
                                                    <div class="card-container">
                                                        <h3 class="card-title"><?php echo $term_single->name ?></h3>
                                                    </div>
                                                </div><!--market-title-->
                                            </div>
		                            	
	                            	<?php endif; ?>
								 </a>
							</div>
                        <?php endif;?>
					<?php endif;?>
		        <?php endforeach;?>
			</div>
        </div>
    </div>
</div>