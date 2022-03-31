<?php get_header();?>
<div class="container">
    <div class="row singleContent ">
        <div class="col-md-9 col-sm-12">
	        <div class="tag-cloud">
	            <section>
	                <i class="fa fa-tags" aria-hidden="true"></i>
	
	                <?php   // Get terms for post
	                $terms = get_the_terms( $post->ID , 'country' );
	                // Loop over each item since it's an array
	                foreach ( $terms as $term ) {?>
	                    <?php $termlinks = get_term_link($term);?>
	                    <a href="<?php echo $termlinks ;?>" class="badge badge-tag country">
	                       <?php echo $term->name;?>
	                    </a>
	                <?php }
	                ?>
	                <?php   // Get terms for post
	                $terms = get_the_terms( $post->ID , 'snf-communication-types' );
	                // Loop over each item since it's an array
	                foreach ( $terms as $term ) {?>
	                    <?php $termlinks = get_term_link($term);?>
	                    <a href="<?php echo $termlinks ;?>" class="badge badge-tag comm-types">
	                        <?php echo $term->name;?>
	                    </a>
	                <?php }
	                ?>
	                <?php   // Get terms for post
	                $terms = get_the_terms( $post->ID , 'markets' );
	                // Loop over each item since it's an array
	                foreach ( $terms as $term ) {?>
	                    <?php $termlinks = get_term_link($term);?>
	                    <a href="<?php echo $termlinks ;?>" class="badge badge-tag markets">
	                        <?php echo $term->name;?>
	                    </a>
	                <?php }
	                ?>
	            </section>
		        <div class="mt-3 mb-3">
			        <?php the_field( 'content' ); ?>
			        <?php if ( get_field( 'file' ) ) : ?>
			            <a class="btn btn-info mr-2" href="<?php the_field( 'file' ); ?>">Download File</a>
			        <?php endif; ?>
			        <?php $url_redirect = get_field( 'url_redirect' ); ?>
			        <?php if ( $url_redirect ) : ?>
			            <a class="btn btn-primary" href="<?php echo esc_url( $url_redirect ); ?>">Read More</a>
			        <?php else:?>
                        <?php if(is_single()):?>
                        <?php else:?>
			            <a class="btn btn-primary" href="<?php the_permalink();?>">Read More</a>
                        <?php endif; ?>
			        <?php endif; ?>
		        </div>
		        <div class="text-center col-sm-12">
		            <a class="btn btn-sm btn-outline-primary" href="<?php echo home_url('/');?>news">Back to All News</a>
		        </div>
	        </div><!--tag-cloud-->
        </div>
		<div class="col-md-3 col-sm-12 articlesSideBar">
            <aside class=" list-group newsSideBar">
                <h3 class="sidebarTitle">You May Also Like:</h3>
                <?php
                // the query
                $the_query = new WP_Query( array(
                    'post_type' =>  'global-communication',
                    'posts_per_page' => 3,
                ));
                ?>
                <ul>
                    <?php if ( $the_query->have_posts() ) : ?>
                        <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                            <li class="list-group-item">
                                <a href="<?php the_permalink();?>">
                                    <h4 class="recentPosts"><?php the_title(); ?></h4>
                                </a>
                            </li>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php else : ?>
                        <p><?php __('No News'); ?></p>
                    <?php endif; ?>
                </ul>
            </aside><!--newsSideBar-->
    	</div><!--articlesSideBar-->
	</div>
    <div class="row">
        <section class="contact-bar col-sm-12 bg-fixed text-white bg-dark" style="background-image: url(<?php bloginfo('template_directory'); ?>/resources/images/CraterLakeHeroImage-scaled.jpg)">

            <div class="section-header">
                <div class="site-cta-container">
                    <div class="text-center">
                        <h2>Contact SNF Today!</h2>
                    </div>
                    <hr>
                    <div class="text-center">
                        <a class="btn btn-outline-light text-white" href="<?php echo home_url('/') ; ?>contact">Lets Work Together</a>
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>


<?php get_footer();?>
