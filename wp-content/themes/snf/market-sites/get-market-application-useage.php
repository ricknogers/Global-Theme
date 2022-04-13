<div class="row mb-5 mt-2">
    <div id="markets_carousel" class="carousel slide col-sm-12 mt-3 mb-3 " data-ride="carousel">
        <div class="row mb-3">
            <div class="col-sm-12">
                <a class="left carousel-control-prev" href="#markets_carousel" role="button" data-slide="prev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control-next " href="#markets_carousel" role="button" data-slide="next">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <?php $market_terms = get_the_terms(get_the_ID(), 'markets');?>
        <?php if( $market_terms ): ?>
            <?php foreach( $market_terms as $market_term ): ?>
            	
	                <?php $application_usage = get_field('application_usage', $market_term->taxonomy . '_' . $market_term->term_id);?>
			        <?php $marketRedirectURL = get_field('page_url', 'category_'.$market_term->term_id); ?>
			        <?php $breadcrumbsColor = get_field('color_scheme', $market_term->taxonomy . '_' . $market_term->term_id);?>
	                <?php if ( $application_usage ) : ?>
	                    <?php $get_terms_args = array(
	                        'taxonomy' => 'product-applications',
	                        'hide_empty' => 0,
	                        'include' => $application_usage,
	                    ); ?>
	                    <?php $terms = get_terms( $get_terms_args ); ?>
	                    <div class="carousel-inner row  w-100 mx-auto " role="listbox">
	                        <?php $i == 0; if ( $terms ) : ?>
	                            <?php foreach ( $terms as $term ) : ?>
	                            	<?php if (!empty($term->description   )):?>
	                                <div class="carousel-item col-md-4  <?php if ($i == 0):?> active <?php endif;?>">
	                                    <div class="cta_card_slider card h-100 shadow-sm">
		                                    <?php $image = get_field( 'image', $term->taxonomy . '_' . $term->term_id);?>
		                                    <?php if ( $image ) : ?>
	                                            <div class="bg_image_card" style="background-image: url(<?php echo $image['url']; ?>)"></div>
		                                    <?php endif; ?>
	                                        <div class="card-body">
	                                            <h4 class="card-title display-5"><?php echo $term->name ;?></h4>
	                                            <p><?php echo $term->description ;?></p>
	                                        </div>
	                                        <div class="card-footer bg-transparent border-0">
		                                        <?php $button_link = get_field( 'application_redirection', $term->taxonomy . '_' . $term->term_id);?>
		                                        <?php if ( $button_link ) : ?>
	                                            <div class="snf-link-wrapper ">
	                                                <div class="snf-link">
	                                                    <a  class="product-list-link" href="<?php echo esc_url( $button_link['url'] ); ?>" target="<?php echo esc_attr( $button_link['target'] ); ?>"><?php echo esc_html( $button_link['title'] ); ?></a>
	                                                </div>
	                                            </div>
		                                        <?php endif; ?>
	                                        </div>
	                                    </div>
	                                </div> <?php $i++;?>
	                                <?php endif; ?>
	                            <?php endforeach; ?>
	                        <?php endif; ?>
	                    </div>
	                <?php endif; ?>
            <?php endforeach; ?>
        <?php else:?>
        <?php endif; ?>
    </div>
</div>