<?php get_header();?>
<div class="container">
    <div class="row singleContent ">
        <div class="col-md-9 col-sm-12">
            <div class="row mt-3 mb-3">
                <div class="col-sm-12">
                    <?php the_field( 'content' ); ?>
                    <?php if ( get_field( 'file' ) ) : ?>
                        <a class="btn btn-info mr-2" href="<?php the_field( 'file' ); ?>">Download File</a>
                    <?php endif; ?>

                </div>
                <div class="text-center col-sm-12 mb-3 mt-3">
                    <a class="btn btn-sm btn-outline-dark font-weight-bold" href="<?php echo home_url('/');?>news">Back to All News</a>
                </div>
                <section class="contact-bar bg-fixed text-white bg-dark d-md-block d-none" >
                    <div class="container p-0">
                        <div class="contact_bg_bar"  style="background-image: url(<?php bloginfo('template_directory'); ?>/resources/images/CraterLakeHeroImage-scaled.jpg)">
                            <header class="section-header">
                                <div class="text-center">
                                     <h2>Contact SNF Today!</h2>
                                </div>
                                <hr class="diamond">
                                <div class="text-center">
                                    <a class="btn btn-outline-light text-white" href="<?php echo home_url('/') ; ?>contact">Lets Work Together</a>
                                </div>
                            </header>
                        </div>
                    </div>
                </section>
            </div>
        </div>
		<div class="col-md-3 col-sm-12 articlesSideBar border-left">
            <aside class=" list-group newsSideBar">
                <section class="sidebar-widget">
                    <h3 class="sidebarTitle">Social Share</h3>
	                <?php my_share_buttons(); ?>

                </section>
	            <section class="sidebar-widget">
                	<h3 class="sidebarTitle pb-2">You May Also Like:</h3>
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
	            </section>
                <section class="sidebar-widget">
					<h3 class="sidebarTitle">Categories</h3>
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

                
            </aside><!--newsSideBar-->
    	</div><!--articlesSideBar-->
	</div>
    
</div>


<?php get_footer();?>
