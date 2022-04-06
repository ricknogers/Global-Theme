<?php
/**
 * The template for displaying Archive pages.
 *
 * @package SNF Group
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div id="primary" class="content-area col-sm-8 col-xs-12 taxonomy-list-elements ">
            <?php
            $term_id = get_queried_object()->term_id;
            $posts = get_posts(array(
                'post_type' =>  'document',
                'order' => 'ACS',
                'posts_per_page' => -1,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'investors',
                        'field'    => 'term_id',
                        'terms'    => array($term_id),
                    ),
                ),
            ));
            $years = array();
            if( $posts ) {
                foreach( $posts as $post ) {
                    setup_postdata( $post );
                    // get date
                    $date = date_create( get_field('doc_date') );
                    // get year
                    $year = date_format($date,'Y');
                    // create new year if not already exists
                    if( !isset( $years[ $year ]) ) {
                        $years[ $year ] = array(
                            'title' =>  $year,
                            'posts' => array()
                        );
                    }
                    // add post to year
                    $years[ $year ]['posts'][] = $post;
                }
                wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly
            }
            $c = 1;
            if( $years ){
                foreach( $years as $year ){?>
                    <div class="row">
                        <div class="col-sm-12 card investorYearDescription">
                            <div class="yearly-title">
                                <h3><?php echo  $year['title'] ;?> </h3>
                                <?php // echo var_dump($year['title']);?>
                            </div>
                            <?php if( $year['posts'] ) { ?>
                                <ul class="list-group">
                                    <?php foreach( $year['posts'] as $post ) {
                                        setup_postdata( $post ) ; $c++;
                                        // get date
                                        $date = date_create( get_field('doc_date') ); ?>
                                        <li class="list-group-item">
                                            <?php if(get_field('doc_fichier')):?>
                                                <a href="<?php echo the_field('doc_fichier');?>">
                                                    <div class="taxonomy-card">
                                                        <div class="taxonomy-card-title">
                                                            <?php echo  get_the_title( $query->post->ID ) ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php else:?>
                                                <a href="<?php the_permalink();?>">
                                                    <div class="taxonomy-card">
                                                        <div class="taxonomy-card-title">
                                                            <?php echo  get_the_title( $query->post->ID ) ?>
                                                        </div><!--taxonomy-card-title-->
                                                    </div><!--taxonomy-card-->
                                                </a>
                                            <?php endif;?>
                                        </li>
                                    <?php }?>
                                </ul>
                            <?php   }?>
                        </div>
                    </div>
                <?php }?>
                <?php wp_reset_postdata(); // IMPORTANT - reset the $post object so the rest of the page works correctly?>
            <?php   }?>
        </div><!-- #primary -->
        <aside class="col-sm-4 col-xs-12 " id="sidebar" role="complementary">
            <?php get_sidebar('investor'); ?>
        </aside>
    </div>
</div>
<?php get_footer(); ?>