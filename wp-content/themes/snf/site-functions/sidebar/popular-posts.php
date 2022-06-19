<?php
/**
*  DIY Popular Posts for News and Single Posts @ https://digwp.com/2016/03/diy-popular-posts/
	**/
	function shapeSpace_popular_posts($post_id) {
		$count_key = 'popular_posts';
		$count = get_post_meta($post_id, $count_key, true);
		if ($count == '') {
			$count = 0;
			delete_post_meta($post_id, $count_key);
			add_post_meta($post_id, $count_key, '0');
		} else {
			$count++;
			update_post_meta($post_id, $count_key, $count);
		}
	}
	function shapeSpace_track_posts($post_id) {
		if (!is_single()) return;
		if (empty($post_id)) {
			global $post;
			$post_id = $post->ID;
		}
		shapeSpace_popular_posts($post_id);
	}
	add_action('wp_head', 'shapeSpace_track_posts');


add_filter( 'wpc_theme_dependencies', 'my_theme_dependencies' );
function my_theme_dependencies( $theme_dependencies ){

	/* my_theme_folder_name - name of the directory of your current WordPress
	theme. All letters should be lowercase even if the theme directory
	has uppercase letters. If you use child theme, you should specify
	parent theme name */

	$theme_dependencies['snf'] = array(
		// CSS selector of the container included posts. E.g. '#primary' or
		// '.main-wrapper .posts'

		// Array with names of the do_action() hooks of your theme, where you want
		// to display button, that opens Filters widget E.g. 'before_main_content'
		'button_hook'       => array('generate_before_main_content'),
		// Array with names of the do_action() hooks of your theme, where you want
		// to display chips. E.g. 'before_posts_list'
		// Don't forget to add Chips list on the 404 page.

	);

	return $theme_dependencies;
}