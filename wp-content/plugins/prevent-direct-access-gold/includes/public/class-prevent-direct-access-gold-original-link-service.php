<?php
/**
 * User: gaupoit
 * Date: 8/28/18
 * Time: 10:04
 *
 * @package pda_services
 */

if ( ! class_exists( 'PDA_Original_Link_Services' ) ) {
	/**
	 * Service class that containing the helper functions in order to interact with the original links.
	 *
	 * Class PDA_Private_Link_Services
	 */
	class PDA_Original_Link_Services {
		/**
		 * Fetch protected files
		 *
		 * Code example: https://gist.github.com/bwps/f22b9d86894ac8a45c25369bb726cb9c
		 *
		 * @return array
		 */
		public static function fetch() {
			$repo     = new PDA_v3_Gold_Repository();
			$post_ids = $repo->get_all_post_id_protect();

			return array_map( function ( $item ) {
				$post = get_post( $item->post_id );

				return array(
					'ID'         => $post->ID,
					'post_title' => empty( $post->post_title ) ? '(no title)' : $post->post_title,
				);
			}, $post_ids );
		}
	}
}
