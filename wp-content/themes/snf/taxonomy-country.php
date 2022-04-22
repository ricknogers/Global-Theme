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
	            // get the currently queried taxonomy term, for use later in the template file
	            $countryName = get_queried_object();

	            ?>
            </div>
            <div class="col-md-12 col-sm-12 contact-info-container">
                <h2>Contact Information</h2>
                <div class=" country-contact">
                        <!-- Start the Loop. -->
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

								<?php
                                    $SubsidiaryName = get_field_object('field_6181a0782331a');
                                    $continent = get_field_object('field_6181a0b52331e');
                                    $UsualName = get_field_object('field_6181a0832331b');
                                    $GeneralPhoneNumber = get_field_object('field_6181a0f423321');
                                    $GeneralEmailAddress = get_field_object('field_6181a10a23322');
                                    $Address = get_field_object('field_6181a11d23323');
                                    $City = get_field_object('field_6181a12e23324');
                                    $State = get_field_object('field_6181a14323326');
                                    $PostalCode = get_field_object('field_6181a13423325');
                                    $FacilityType = get_field_object('field_6181a15823327');
								?>
                                <div class="card mb-3 shadow" >
                                    <div class="row no-gutters">
                                        <div class="col-md-4">
											<?php
											// Checking the page/post/taxonomy if it is categorized specifically by country then retrieves that Term ACF Fields
											$term_array = array();
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
												<?php $icon = get_field('country_fallback_image', $term->taxonomy . '_' . $term->term_id);?>
                                                <div class="archive-inner-banner-image   "style="background-image:url('<?php echo $icon['url']; ?>');"></div>
											<?php endforeach;?>

											<?php ;?>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-header">
												<?php if(get_sub_field('subsidiary_name')):?>
                                                    <h5 class="card-title"><?php the_sub_field('subsidiary_name') ?></h5>
												<?php endif;?>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group list-group-flush">
													<?php if(get_sub_field('continent')):?>
                                                        <li class="list-group-item">  <i class="bi bi-globe"></i> | <b><?php the_sub_field('continent') ?></b></li>
													<?php endif;?>
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
                                        </div>
                                    </div>
                                </div>

<?php endwhile; else : ?>
                    <?php endif; ?>


                </div>
            </div>
        </div>
    </div>
<?php get_footer(); ?>