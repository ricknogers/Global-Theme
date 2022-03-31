<?php
/**
 * The template used for displaying list of posts.
 *
 * @package SNF Subsidiary
 */
?>
    <article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
        <div class="entry-content here">
            <div class="entry-summary">
                <?php the_content(); ?>
            </div>
        </div><!-- .entry-summary -->
    </article><!-- #content-## -->