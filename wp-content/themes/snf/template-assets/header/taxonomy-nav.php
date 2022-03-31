<?php if(is_tree('80431')):?> <?php // if page is decendent of /industry page ;?>
    <div class=" " id="custom-tax-second-nav">
	    <?php   $term_array = array();
	        $term_list = wp_get_post_terms($post->ID, 'markets', array(
	                "fields" => "all",
	                'orderby' => 'parent',
	                'order' => 'ASC'
	            )
	        );
	        foreach($term_list as $term_single) {
	            $term_array[] = $term_single->name ; //do something here
	        }
	        ?>
        <div class="col second-tier-nav subsidiary-children-pages  h-100 " style="<?php foreach($term_list as $term) :?><?php $breadcrumbsColor = get_field('color_scheme', $term->taxonomy . '_' . $term->term_id);?>background-color:<?php echo $breadcrumbsColor;?><?php endforeach; ?>">
            <div class="page-listing-positioning ">
                <ul class="list-group list-group-horizontal">
                    <?php foreach($term_list as $term) :?>
                        <?php $termLink = get_field('page_url', 'category_'.$term->term_id); ?>
                        <li class="list-group-item active " style="background-color:<?php echo $breadcrumbsColor;?>">
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
                    <?php global $post; // assuming there is global $post already set in your context ?>
                    <?php if ( $post->post_parent ) :  // if it's a child ;?>
                        <?php  $siblings = new WP_Query( array(
                            'post_type' => 'page',
                            'post_parent' => $post->post_parent,
                            'post__not_in' => array( $post->ID )
                            ) ); ?>
                        <?php if ( $siblings->have_posts() ) :?>
                            <?php while ( $siblings->have_posts() ) : $siblings->the_post(); ?>
                                <li class="list-group-item"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php foreach($term_list as $term) :?>
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
					<?php  $term_array = array();
			        $term_list = wp_get_post_terms($post->ID, 'country', array(
			                "fields" => "all",
			                'orderby' => 'parent',
			                'order' => 'ASC'
			            )
			        );
			        foreach($term_list as $term_single) {
			            $term_array[] = $term_single->name ; //do something here
			        }
			        ?>
			        <?php foreach($term_list as $term) :?>
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
			         <?php foreach($term_list as $term) :?>
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