<?php
/**
 * The template used for displaying a post
 *
 * @package SNF Subsidiary
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="page-header">
		<h1 class="page-title"><?php the_title(); ?></h1>
	</div><!-- .entry-header -->
	<div class="page-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->