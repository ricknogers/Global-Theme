<aside class="sidebar  ">
    <div class="card border-primary mb-3" style="max-width: 18rem;">
        <div class="card-header bg-transparent">
            <h4 class="display-5">Contact addresses</h4>
            <img src="<?php bloginfo('template_directory'); ?>/resources/images/contact-world-display.png" alt="SNF Country Contact Map" class="img-fluid mx-auto d-block">
        </div>
        <div class="card-body text-primary">

            <div class="location-cats-dropdown">
                <form id="location-category-select" class="location-category-select" method="get">
                    <?php $loc_cats = get_terms( array(
                        'taxonomy' => 'country',
                        'hide_empty' => true,
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                    ) );
                    if ( $loc_cats ) : ?>
                        <select name="location_category" id="location_category" class="form-control">
                            <option value="0">Choose a region</option>
                                <?php foreach( $loc_cats as $loc_cat ) : ?>
                                    <option value="<?php echo $loc_cat->slug; ?>" id="term-id-<?php echo $loc_cat->term_id; ?>"><?php echo $loc_cat->name; ?></option>
                                <?php endforeach; ?>
                        </select>
                    <?php endif;
                    wp_reset_postdata(); ?>
                </form>
            </div><!--location-cats-dropdown-->
            <?php foreach( get_terms( 'country', array( 'hide_empty' => true, 'depth'=> 1, 'parent' => 0 ) ) as $parent_term ) :?>
                <?php foreach( get_terms( 'country', array( 'hide_empty' => true, 'parent' => $parent_term->term_id ) ) as $child_term ) :?>
                    <?php $link = get_field('country_page_redirect', 'category_'.$child_term->term_id); ?>
                    <?php $continent = get_sub_field('continent', 'category_'.$child_term->term_id); ?>
					<?php if ( have_rows( 'subsidiary_information', $child_term) ) : ?>
						<?php while ( have_rows( 'subsidiary_information',  $child_term ) ) : the_row(); ?>
							<section class="output">
		                    <div id="<?php echo $child_term->slug;?>" class="country-list <?php echo $child_term->slug;?>">
		                        <h6 class="text-muted">Region: <?php echo $parent_term->name; ?></h6>
		                        <a href="<?php echo $link['url'];?>">
		                        <h3><?php echo $child_term->name;?></h3>
		                        </a>
		                       <ul class="list-group list-group-flush">
			                      
			                            <li class="list-group-item">  <i class="bi bi-globe"></i> | <b><?php echo $continent; ?></b></li>
			                        
			                        <?php if(get_sub_field('general_phone_number')):?>
			                            <li class="list-group-item"><i class="bi bi-telephone"></i> | <b><?php the_sub_field('general_phone_number') ?></b></li>
			                        <?php endif;?>
			                        <?php if(get_sub_field('general_email_address')):?>
			                            <li class="list-group-item"><i class="bi bi-envelope-check"></i> | <b><?php the_sub_field('general_email_address') ?></b></li>
			                        <?php endif;?>
			                        <?php if(get_sub_field('address')):?>
			                            <li class="list-group-item"> <i class="bi bi-geo"></i> | <b><?php the_sub_field('address') ?> <?php the_sub_field('city') ?> <?php the_sub_field('state') ?> <?php the_sub_field('postal_code') ?></b></li>
			                        <?php endif;?>
			                        <?php if(get_sub_field('facility_type')):?>
			                            <li class="list-group-item"><i class="bi bi-building"></i> | <b><?php the_sub_field('facility_type') ?></b></li>
			                        <?php endif;?>
			
			
			                    </ul>
		
		              
		                    </div>	
							</section>
		                    <?php endwhile; ?>
					<?php else : ?>
						<?php // No rows found ?>
					<?php endif; ?>
                <?php endforeach;?>
            <?php endforeach;?>
        </div><!--card-body-->
    </div>
</aside>

<script>
    (function($, window){
        $(document).ready(function(){   // Makes sure the code contained doesn't run until
            
            $('select#location_category').change(function(){
	            $('.country-list').hide();
                $('#' + $(this).val()).show();
            });
        });
    })(jQuery, window);
</script>