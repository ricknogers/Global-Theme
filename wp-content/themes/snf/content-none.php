<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * @package SNF Subsidiary
 */
?>
<article>
	<header class="page-header">
		<h1 class="page-title">OOPS!</h1>
	</header><!-- .page-header -->
	<div class="page-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
			<p>Ready to publish your first post? <a href="<?php esc_url( admin_url( 'post-new.php' ) ); ?>">Get started here</a></p>
		<?php elseif ( is_search() ) : ?>
			<p>Sorry, but nothing matched your search terms. Please try again with some different keywords</p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p>It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help</p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div><!-- .page-content -->
</article><!-- .no-results -->