<?php
/**
 * SNF Group
 *
 * @package snf_group
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function snf_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'snf_customize_register' );
//
//
////define the custom post type and custom taxonomy
//define("MENU_CPT", "page");
//define("MENU_CT", "country");
//
////custom function for selecting posts based on a term
//function get_posts_by_term($term_id, $post_type=MENU_CPT, $taxonomy=MENU_CT) {
//	$args = array(
//		'posts_per_page' => -1,
//		'post_type' => $post_type,
//		'tax_query' => array(
//			array(
//				'taxonomy' => $taxonomy,
//				'field' => 'id',
//				'terms' => $term_id
//			)
//		)
//	);
//	return get_posts( $args );
//}
//
////custom nav menu walker class
//class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
//	/**
//	 * Display array of elements hierarchically.
//	 *
//	 * It is a generic function which does not assume any existing order of
//	 * elements. max_depth = -1 means flatly display every element. max_depth =
//	 * 0 means display all levels. max_depth > 0  specifies the number of
//	 * display levels.
//	 *
//	 * @since 2.1.0
//	 *
//	 * @param array $elements
//	 * @param int $max_depth
//	 * @return string
//	 */
//	function walk( $elements, $max_depth) {
//
//		$args = array_slice(func_get_args(), 2);
//		$output = '';
//
//		if ($max_depth < -1) //invalid parameter
//			return $output;
//
//		if (empty($elements)) //nothing to walk
//			return $output;
//
//		$id_field = $this->db_fields['id'];
//		$parent_field = $this->db_fields['parent'];
//
//		// flat display
//		if ( -1 == $max_depth ) {
//			$empty_array = array();
//			foreach ( $elements as $e )
//				$this->display_element( $e, $empty_array, 1, 0, $args, $output );
//			return $output;
//		}
//
//		/*
//		 * need to display in hierarchical order
//		 * separate elements into two buckets: top level and children elements
//		 * children_elements is two dimensional array, eg.
//		 * children_elements[10][] contains all sub-elements whose parent is 10.
//		 */
//		$top_level_elements = array();
//		$children_elements  = array();
//		foreach ( $elements as $e) {
//			if ( 0 == $e->$parent_field )
//			{
//				$top_level_elements[] = $e;
//				if ( $e->type=='taxonomy' && $e->object == MENU_CT ) {
//
//					$taxonomy_posts = get_posts_by_term($e->object_id);
//
//					foreach ( $taxonomy_posts as $tax_post ) {
//						$tax_post = wp_setup_nav_menu_item($tax_post);
//						$tax_post->post_type = 'nav_menu_item';
//						$tax_post->menu_item_parent = $e->$id_field;
//						$tax_post->object = 'custom';
//						$tax_post->type = 'custom';
//						$tax_post->ID = $e->$id_field.$tax_post->ID;
//						$children_elements[ $e->$id_field ][] = $tax_post;
//						$children_elements_classes[] = $tax_post;
//					}
//				}
//			}
//			else
//			{
//				$children_elements[ $e->$parent_field ][] = $e;
//			}
//		}
//
//		/*
//		 * when none of the elements is top level
//		 * assume the first one must be root of the sub elements
//		 */
//		if ( empty($top_level_elements) ) {
//
//			$first = array_slice( $elements, 0, 1 );
//			$root = $first[0];
//
//			$top_level_elements = array();
//			$children_elements  = array();
//			foreach ( $elements as $e) {
//				if ( $root->$parent_field == $e->$parent_field )
//				{
//					$top_level_elements[] = $e;
//					if ( $e->type=='taxonomy' && $e->object == MENU_CT ) {
//
//						$taxonomy_posts = get_posts_by_term($e->object_id);
//
//						foreach ( $taxonomy_posts as $tax_post ) {
//							$tax_post = wp_setup_nav_menu_item($tax_post);
//							$tax_post->post_type = 'nav_menu_item';
//							$tax_post->menu_item_parent = $e->$id_field;
//							$tax_post->object = 'custom';
//							$tax_post->type = 'custom';
//							$tax_post->ID = $e->$id_field.$tax_post->ID;
//							$children_elements[ $e->$id_field ][] = $tax_post;
//							$children_elements_classes[] = $tax_post;
//						}
//					}
//				}
//				else
//				{
//					$children_elements[ $e->$parent_field ][] = $e;
//				}
//			}
//		}
//
//		//assing the classes to our dynamically populated posts
//		if ( $children_elements_classes )
//			_wp_menu_item_classes_by_context($children_elements_classes);
//
//		foreach ( $top_level_elements as $e )
//			$this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );
//
//		/*
//		 * if we are displaying all levels, and remaining children_elements is not empty,
//		 * then we got orphans, which should be displayed regardless
//		 */
//		if ( ( $max_depth == 0 ) && count( $children_elements ) > 0 ) {
//			$empty_array = array();
//			foreach ( $children_elements as $orphans )
//				foreach( $orphans as $op )
//					$this->display_element( $op, $empty_array, 1, 0, $args, $output );
//		}
//
//		return $output;
//	}
//}



