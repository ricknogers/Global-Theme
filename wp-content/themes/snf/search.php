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
                <div class="col-md-12 col-sm-12" >
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                        <?php
                       // $total = $wp_query->found_posts + $wp_query->found_terms;
                        // $wp_query->max_num_pages = ceil( $total / $wp_query->terms_per_page );
                        ;?>
                        <?php if (  in_array( get_post_type(), array( 'timeline','post', 'global-communication','marketing-material', 'products', 'page',  ) ) )  { ?>
                            <?php get_template_part('template-assets/search/search-results-cpt-card');?>
                        <?php } ?>
                        <?php if ( is_tax( array('snf-communication-types', 'country','markets','product-applications') ) )  { ?>
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
