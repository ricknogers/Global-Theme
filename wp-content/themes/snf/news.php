<?php
/**
 * Template Name: News
 *

 */

get_header();?>
<div class="container">
    <div class="row">
        <div class="col-md-3 col-xs-12 card card-filter" style="height: 100%;">
	        <form class="card-body" >
                <?php echo do_shortcode('[fe_widget id="80404"]'); ?>
	        </form>
            <div class="row">
                <div class="col-md-12 col-xs-12 newsSidebarWrapper">
                    <hr class="center-diamond">
                    <div class="col newsSidebarCategories">
                        <section class="topLevelSidebar">
                            <h2 class="font-weight-bold">Categories</h2>
                            <div class="col-sm-12 newsCategoryList">
                                <?php $wcatTerms = get_terms('snf-communication-types', array('hide_empty' => 0, 'parent' =>0));
                                foreach($wcatTerms as $wcatTerm) :
                                    ?>
                                    <ul class="list-group">
                                        <li class='list-group-item'>
                                            <a href="<?php echo get_term_link( $wcatTerm->slug, $wcatTerm->taxonomy ); ?>"><?php echo $wcatTerm->name; ?></a>
                                        </li>
                                    </ul>
                                <?php endforeach; ?>
                            </div><!--news category list-->
                        </section>
                    </div><!--newsSidebarCategories-->
                    <hr class="center-diamond">
                    <div class="col newsSidebarCategories">
                        <section class="topLevelSidebar">
                            <h2 class="font-weight-bold">Similar Posts</h2>
                            <ul class="list-group">
	                           
                            </ul>
                        </section>
                    </div><!--newsSidebarCategories-->
                </div><!--newsSidebarWrapper-->
            </div><!--row-->
        </div>
        <div class="col-md-9 col-xs-12 latestNews communication-feed">
            <div class="newsCategoryWrapper" id="filtered_lists">
                <?php
                $temp =  $query;
                $the_query = null;
                $args = array(
                    'post_type' => 'global-communication',
                    'posts_per_page' => '8',
                );
                $the_query = new WP_Query( $args );
                ?>
                <?php if ( $the_query->have_posts() ) :?>
                    <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                        <div class="media  ">
                            <div class="row mb-3 h-100">
                                <?php if ( get_field( 'file_preview_image' ) ) : ?>
                                    <div class="media-body col-10 ">
                                        <h5 class="mt-0 mb-1 display-4"><?php the_title();?></h5>
                                        <div class="tag-cloud  ">
                                            <?php get_template_part('template-assets/misc/tag-cloud');?>
                                        </div><!--tag cloud-->
                                        <p class="lead"><?php echo custom_field_excerpt(); ?></p>
                                        <div class="row">
                                            <div class="btn-bar col">
                                                <?php if ( get_field( 'file' ) ) : ?>
                                                    <a class="btn btn-sm btn-outline-primary " href="<?php the_field( 'file' ); ?>">Download File</a>
                                                <?php endif; ?>
                                                <?php $url_redirect = get_field( 'url_redirect' ); ?>
                                                <?php if ( $url_redirect ) : ?>
                                                    <a class="btn btn-sm  btn-outline-dark" href="<?php echo esc_url( $url_redirect ); ?>">Read More</a>
                                                <?php else:?>
                                                    <a class="btn btn-sm  btn-outline-dark " href="<?php the_permalink();?>">Read More</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="media-image col-2">
                                        <img class="ml-3 border align-self-center rounded img-fluid" src="<?php the_field( 'file_preview_image' ); ?>" />
                                    </div>
                                <?php else:?>
                                    <div class="media-body col ">
                                        <h5 class="mt-0 mb-1 display-4"><?php the_title();?></h5>
                                        <div class="tag-cloud  ">
                                            <?php get_template_part('template-assets/misc/tag-cloud');?>
                                        </div><!--tag cloud-->
                                        <p class="lead"><?php echo custom_field_excerpt(); ?></p>
                                        <div class="row">
                                            <div class="btn-bar col">
                                                <?php if ( get_field( 'file' ) ) : ?>
                                                    <a class="btn btn-sm btn-outline-primary " href="<?php the_field( 'file' ); ?>">Download File</a>
                                                <?php endif; ?>
                                                <?php $url_redirect = get_field( 'url_redirect' ); ?>
                                                <?php if ( $url_redirect ) : ?>
                                                    <a class="btn btn-sm  btn-outline-dark" href="<?php echo esc_url( $url_redirect ); ?>">Read More</a>
                                                <?php else:?>
                                                    <a class="btn btn-sm  btn-outline-dark " href="<?php the_permalink();?>">Read More</a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php if ($the_query->max_num_pages > 1) : // custom pagination  ?>
                        <?php $orig_query = $wp_query;  $wp_query = $the_query; ?>
                        <div class=" col-sm-12 pagination pagination-wrapper">
                            <?php echo bootstrap_pagination(); ?>
                        </div>
                    <?php endif; ?>
                <?php wp_reset_postdata();?>
                <?php else:?>
                    <p>Sorry, there are no posts to display</p>
                <?php endif; ?>
            </div>
        </div><!-- latestNews -->
    </div>
</div>
<?php get_footer();?>