<?php if(is_tree('80431')):?> <?php // if page is decendent of /industry page ;?>
    <div class=" " id="custom-tax-second-nav">
	    <?php $terms = get_the_terms(get_the_ID(), 'markets');?>

        <div class="col second-tier-nav subsidiary-children-pages  h-100 " style="<?php foreach($terms as $term) :?><?php $breadcrumbsColor = get_field('color_scheme', $term->taxonomy . '_' . $term->term_id);?>background-color:<?php echo $breadcrumbsColor;?><?php endforeach; ?>">
            <div class="page-listing-positioning ">
                <ul class="list-group list-group-horizontal">

	                <?php global $post; // assuming there is global $post already set in your context ?>

	                <?php foreach($terms as $term) :?>
		                <?php $termLink = get_field('page_url', 'category_'.$term->term_id); ?>
                        <li class="list-group-item active " >
                            <a href="<?php echo $termLink ;?>">
                                <h3 class="display-5"><?php echo $term->name;?></h3>
                            </a>
                        </li>
	                <?php endforeach; ?>
	                <?php $args = [
		                'child_of' => $post->ID,
		                'title_li' => '',
		                'depth' => 0,
	                ];
	                wp_list_pages( $args ); ?>
	                <?php if ( $post->post_parent ) :  // if it's a child ;?>
		                <?php
		                if ( $post->post_parent ) {
			                $children = wp_list_pages( array(
				                'title_li' => '',
				                'child_of' => $post->post_parent,
				                'echo'     => 0
			                ) );
		                } else {
			                $children = wp_list_pages( array(
				                'title_li' => '',
				                'child_of' => $post->ID,
				                'echo'     => 0
			                ) );
		                }
		                ?>
		                <?php echo $children; ?>
	                <?php endif; ?>
	                <?php foreach($terms as $term) :?>
                        <li class="list-group-item">
                            <a href="<?php echo home_url('/');?>news/facet_locations-<?php echo $term->slug;?>">
                                News
                            </a>
                        </li>
	                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif;?>
<?php if( is_tree('102') && !(is_page('locations'))  ):?>
	<div class=" " id="custom-tax-second-nav">
	    <div class="col second-tier-nav subsidiary-children-pages  h-100 bg-transparent">
	        <div class="page-listing-positioning  "  >
	            <ul class="list-group list-group-horizontal">
		            <?php $terms = get_the_terms(get_the_ID(), 'country');?>

                    <?php foreach($terms as $term) :?>
			        <?php $termLink = get_field('country_page_redirect', 'category_'.$term->term_id); ?>
                        <?php  $url = get_term_link($term->slug, 'country');?>
			            <li class="list-group-item active " style="background-color:<?php echo $breadcrumbsColor;?>">
			                <?php if($termLink){?>
                                <a href="<?php echo $termLink ['url'];?>">
                                    <h3 class="display-5"><?php echo $term->name;?></h3>
                                </a>
                            <?php }else{?>
                             <a href="<?php echo $url ['url'] ;?>">
                                    <h3 class="display-5"><?php echo $term->name;?></h3>
                                </a>
                            <?php }?>
			            </li>
			        <?php endforeach; ?>
		            <?php $args = [
			            'child_of' => $post->ID,
			            'title_li' => '',
			            'depth' => 0,
		            ];
		            wp_list_pages( $args ); ?>

			         <?php foreach($terms as $term) :?>
					    <li class="list-group-item">
					        <a href="<?php echo home_url('/');?>news/facet_locations-<?php echo $term->slug;?>">
					            News
					        </a>
					    </li>
					<?php endforeach; ?>
	            </ul>
	        </div>
	    </div>
	 </div>
<?php endif;?>