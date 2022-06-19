<?php
/**
 * The template for displaying Tax pages.
 *
 * @package SNF
 */

get_header(); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
	            <?php


	            $country_slug = get_queried_object()->slug;
	            $country_name = get_queried_object()->name;
	            ?>



            </div>
            <div class="col-md-12 col-sm-12 contact-info-container">
                <h2>Contact Information</h2>

                <div class=" country-contact">

	                <?php $terms = wp_get_object_terms($post->ID, 'country');?>
                    <?php if(!empty($terms)):?>
	                    <?php foreach($terms as $term):?>
		                    <?php $exampleName = $term->name;?>
		                    <?php $exampleSlugs[] = $term->slug;?>

                            <div class="card mb-3 shadow" >
                                <div class="row no-gutters">
                                    <div class="col-md-4 col-sm-12 ">
                                        <?php $icon = get_field('country_fallback_image', $term->taxonomy . '_' . $term->term_id);?>
                                        <div class="archive-inner-banner-image   "style="background-image:url('<?php echo $icon['url']; ?>');"></div>
                                    </div>
                                    <div class="col-md-8 col-sm-12">
                                        <?php if ( have_rows( 'subsidiary_information' ,$term->taxonomy . '_' . $term->term_id) ) : ?>
                                            <?php while ( have_rows( 'subsidiary_information' ,$term->taxonomy . '_' . $term->term_id) ) : the_row(); ?>
                                                <?php $continent = get_sub_field('continent', $term->taxonomy . '_' . $term->term_id);?>
                                                <?php $subsidiary_name = get_sub_field('subsidiary_name', $term->taxonomy . '_' . $term->term_id);?>
                                                <?php $general_phone_number = get_sub_field('general_phone_number', $term->taxonomy . '_' . $term->term_id);?>
                                                <?php $usual_name = get_sub_field('usual_name', $term->taxonomy . '_' . $term->term_id );?>
                                                <?php $general_email_address = get_sub_field('general_email_address', $term->taxonomy . '_' . $term->term_id);?>
                                                <?php $address = get_sub_field('address', $term->taxonomy . '_' . $term->term_id);?>
                                                <?php $city = get_sub_field('city', $term->taxonomy . '_' . $term->term_id);?>
                                                <?php $state = get_sub_field('state', $term->taxonomy . '_' . $term->term_id);?>
                                                <?php $postal_code = get_sub_field('postal_code', $term->taxonomy . '_' . $term->term_id);?>


                                                <div class="card-header">
                                                    <?php if($subsidiary_name):?>
                                                        <h5 class="card-title"><?php echo $subsidiary_name; ?></h5>
                                                    <?php endif;?>
                                                </div>
                                                <div class="card-body">
                                                    <ul class="list-group list-group-flush">
                                                        <?php if($continent):?>
                                                            <li class="list-group-item">  <i class="bi bi-globe"></i> | <?php echo $continent ?></li>
                                                        <?php endif;?>
                                                        <?php if($general_phone_number):?>
                                                            <li class="list-group-item"><i class="bi bi-telephone"></i> | <?php echo $general_phone_number ?></li>
                                                        <?php endif;?>
                                                        <?php if($general_email_address):?>
                                                            <li class="list-group-item"><i class="bi bi-envelope-check"></i> | <?php echo $general_email_address ?></li>
                                                        <?php endif;?>
                                                        <?php if($address):?>
                                                            <li class="list-group-item"> <i class="bi bi-geo"></i> |  <?php echo $address ?>  </li>
                                                        <?php endif;?>
                                                        <?php if($city && $state):?>
                                                            <li class="list-group-item"><i class="bi bi-building"></i> | <?php echo $city ?>, <?php echo $state;?> <?php echo $postal_code; ?></li>
                                                        <?php endif;?>
                                                    </ul>
                                                </div>
			                                <?php endwhile; ?>
                                        <?php else : ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>


                		<?php endforeach;?>
	                <?php endif;?>









                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>