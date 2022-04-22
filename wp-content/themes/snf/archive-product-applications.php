<?php get_header(); ?>



    <div class="container">
        <div class="row">
            <div id="primary" class="content-area col-md-9 col-sm-12 ">
				<?php

				?>

				<?php if(have_posts()):?>
                    <div class="article-list">
						<?php while ( have_posts() ) : the_post(); ?>
							<?php
							/* Include the Post-Format-specific template for the content.
							* If you want to override this in a child theme, then include a file
							 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
							 */
							get_template_part( 'content', 'archive-applications');
							?>
						<?php endwhile; ?>
                    </div>
				<?php else : ?>
				<?php endif; wp_reset_query(); ?>
            </div><!-- #primary -->
            <div class="col-md-3 col-sm-12 articlesSideBar mt-4">
                <aside class=" list-group newsSideBar " id="sidebar" role="complementary">
					<?php get_sidebar('archive'); ?>

                </aside>
            </div>

        </div>
    </div>

<?php get_footer(); ?>