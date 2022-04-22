<aside class="sidebar  ">
    <div class="card  mb-3" style="border:solid 1px rgb(107,105,135);">
        <div class="card-header bg-transparent">
            <h4 class="display-5 text-uppercase text-center" >Country Contact</h4>
            <img src="<?php bloginfo('template_directory'); ?>/resources/images/contact-world-display.png" alt="SNF Country Contact Map" class="img-fluid mx-auto d-block">
        </div>
        <div class="card-body text-primary">
            <div class="location-cats-dropdown">
                <form id="location-category-select" class="location-category-select" method="get">
                    <?php $terms = get_terms( array(
                        'taxonomy' => 'country',
                        'hide_empty' => true,
                        'orderby' => 'name',
                        'order' => 'DESC',
                    ) );
                    if ( $terms ) : ?>
                        <select name="location_category" id="location_category" class="form-control">
                            <option value="">Choose a region</option>
	                        <?php foreach( get_terms( 'country', array(  'hide_empty' => true, 'depth'=> 1, 'parent' => 0 ) ) as $parent_term ) :?>
	                            <?php foreach( get_terms( 'country', array( 'hide_empty' => true, 'parent' => $parent_term->term_id ) ) as $child_term ) :?>
                                    <option class="country-select " value="<?php echo $child_term->slug; ?>" id="term-id-<?php echo $child_term->term_id; ?>"><?php echo $child_term->name; ?></option>
                                <?php endforeach; ?>
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
							<section class="output"  id="<?php echo $child_term->slug;?>">
                                <div class="country-list <?php echo $child_term->slug;?>">
                                    <h6 class="text-muted text-center text-uppercase mt-2 mb-2">Region: <?php echo $parent_term->name; ?></h6>
                                    <a href="<?php echo $link['url'];?>"><h3 class="country_name text-uppercase"><?php echo $child_term->name;?></h3></a>
                                    <ul class="list-group list-group-flush country_list_elements">
                                        <?php if(get_sub_field('continent')):?>
                                            <li class="list-group-item">
                                                <div class="col-md-2 col-sm-2 p-0 border-right"><i class="bi bi-globe"></i></div>
                                                <div class="col"><p class="lead text-uppercase"><?php the_sub_field('continent') ?></p></div>
                                            </li>
                                        <?php endif;?>
                                        <?php if(get_sub_field('general_phone_number')):?>
                                            <li class="list-group-item">
                                                <div class="col-md-2 col-sm-2 p-0 border-right">
                                                    <i class="bi bi-telephone"></i>
                                                </div>
                                                <div class="col">
                                                    <p class="lead text-uppercase"><?php the_sub_field('general_phone_number') ?></p>
                                                </div>
                                            </li>
                                        <?php endif;?>
                                        <?php if(get_sub_field('general_email_address')):?>
                                            <li class="list-group-item">
                                                <div class="col-md-2 col-sm-2 p-0 border-right">
                                                    <i class="bi bi-envelope-check"></i>
                                                </div>
                                                <div class="col">
                                                    <p class="lead text-uppercase"><?php the_sub_field('general_email_address') ?></p>
                                                </div>
                                            </li>
                                        <?php endif;?>
                                        <?php if(get_sub_field('address')):?>
                                            <li class="list-group-item">
                                                <div class="col-md-2 col-sm-2 p-0 border-right">
                                                    <i class="bi bi-geo"></i>
                                                </div>
                                                <div class="col">
                                                    <p class="lead text-uppercase"><?php the_sub_field('address') ?> <?php the_sub_field('city') ?> <?php the_sub_field('state') ?> <?php the_sub_field('postal_code') ?></p>
                                                </div>
                                            </li>
                                        <?php endif;?>
                                        <?php if(get_sub_field('facility_type')):?>
                                            <li class="list-group-item">
                                                <div class="col-md-2 col-sm-2 p-0 border-right">
                                                    <i class="bi bi-building"></i>
                                                </div>
                                                <div class="col">
                                                    <p class="lead text-uppercase"><?php the_sub_field('facility_type') ?></p>
                                                </div>
                                            </li>
                                        <?php endif;?>
                                        <li class="list-group-item mt-4">
                                            <?php if($link):?>
                                                <a class="btn btn-outline-primary text-white border-0" style="background-color:rgb(107,105,135);" href="<?php echo $link['url'];?>contact"> Contact <?php echo $child_term->name;?></a>
                                            <?php else:?>
	                                            <?php $terms = get_the_terms(get_the_ID(), 'markets');?>
                                                <?php foreach($terms as $term) :?>
                                                    <?php $terms = get_term_link($term);?>

                                                    <a class="btn btn-outline-primary text-white border-0" style="background-color:rgb(107,105,135);" href="<?php echo $termlinks ;?>"> Contact <?php echo $child_term->name;?></a>
	                                            <?php endforeach; ?>
                                            <?php endif;?>
                                        </li>
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
	            $('.output').hide();
                $('#' + $(this).val()).show();
            });
        });
    })(jQuery, window);
</script>