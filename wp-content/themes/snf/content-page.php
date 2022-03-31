<?php
/**
 * The template used for displaying page
 *
 * @package SNF Subsidiary
 */
?>
<div class="container">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
        </div><!-- .page-header -->
        <div class="page-content">
            <?php the_content(); ?>
        </div><!-- .page-content -->
    </article><!-- #page-## -->
</div>