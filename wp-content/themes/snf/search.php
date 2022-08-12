<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package SNF Group 
 */

get_header(); ?>
<div class="container" id="search-container">
    <div class="row">
        <div class="col-md-12 col-sm-12 search-results-container">
           <div class="row">
               <div class="col-sm-12">
	               <?php dynamic_sidebar('search-widget');?>

               </div>
           </div>
            <div class="row">
                <div class="col-md-12 col-sm-12" id="solr-search-results">
	                <?php
	                $args  = array(
		                // solr_integrate required for Solr.
		                'solr_integrate' => true,
		                'post_type'      => 'any',
		                'post_status'    => 'publish',
		                'posts_per_page' => 15,

	                );
	                $query = new WP_Query( $args );?>


	                <?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
                       <?php if (  in_array( get_post_type(), array( 'post', 'global-communication', 'page',  ) ) )  { ?>
                            <?php get_template_part('template-assets/search/search-results-cpt-card');?>
                        <?php } ?>
                        <?php if ( is_tax( array('snf-communication-types', 'country','markets',) ) )  { ?>
                            <?php get_template_part('template-assets/search/search-results-tax-card');?>
                        <?php } ?>
                    <?php endwhile; wp_reset_query(); else: ?>
                        <p>Sorry, no posts matched your criteria.</p>
                        <div class="col-xs-12">
                            <?php get_search_form();?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class=" col-sm-12 pagination-wrapper">
                    <?php echo bootstrap_pagination(); ?>
                </div>
            </div>
            <?php $search=get_search_query(); global $wp_query;global $post;?>
            <?php if ( have_posts() && ($search != null || is_search()) || $post->post_content != '' ) :?>
            <div class="row">
                <div class=" col-sm-12 reSearch">
                    <?php get_search_form();?>
                </div>
            </div>
            <?php endif; wp_reset_query()?>
        </div><!--search-results-container-->
    </div>
</div>
<?php get_footer(); ?>
