<?php
/**
 * Created by PhpStorm.
 * User: linhlbh
 * Date: 6/3/19
 * Time: 09:23
 */


/**
 * For password services
 */
if ( ! class_exists( 'WP_Stats_PDA_Services' ) ) {

	/**
	 * Class PDA_Stats_Service
	 */
	class WP_Stats_PDA_Services {
		/**
		 * @var PDA_Stats_Repository
		 */
		private $pda_repository;

		/**
		 * PDA_Stats_Repository constructor.
		 *
		 * @param PDA_Stats_Repository $pda_repository
		 */
		public function __construct( $pda_repository = null ) {
			$this->pda_repository = is_null( $pda_repository ) ? new PDA_Stats_Repository() : $pda_repository;
		}

		/**
		 * Get Top Files with Most Click
		 *
		 * @param array $data
		 *
		 * @return array
		 */
		public function get_files_which_have_download_clicks( $data ) {
			$files = $this->pda_repository->get_files_which_have_download_clicks( $data );

			return array_map( array( $this, 'map_value_top_ten' ), $files );
		}

		/**
		 * Get Top Download Links with Most Click
		 *
		 * @param array $data
		 *
		 * @return array
		 */
		public function get_private_links_which_have_download_clicks( $data ) {
			$files = $this->pda_repository->get_private_links_have_download_clicks( $data );

			return array_map( array( $this, 'map_value_top_ten' ), $files );
		}

		/**
		 * Get Top Files With Most Download Links
		 *
		 * @param array $data
		 *
		 * @return array
		 */
		public function get_top_files_with_most_download_links( $data ) {
			$files = $this->pda_repository->get_top_files_with_most_download_links( $data );

			return array_map( array( $this, 'map_value_top_ten' ), $files );
		}

		/**
		 * Get Top going-to-expire Download Links
		 *
		 * @param array $data
		 *
		 * @return array
		 */
		public function get_expire_download_links_nearly( $data ) {
			$files = $this->pda_repository->get_expire_download_links_nearly( $data );

			return array_map( array( $this, 'map_value_top_ten' ), $files );
		}

		/**
		 * Get total of private download link and total click for a post
		 *
		 * @param array $data include post_id
		 *
		 * @return array
		 */
		public function stat_for_private_download_link( $data ) {
			$results_total_private_download_link = $this->pda_repository->get_total_private_download_link( $data );
			$results_total_click                 = $this->pda_repository->get_total_click_to_private_download_link( $data );

			return [
				'total_private_link' => $results_total_private_download_link,
				'total_click'        => $results_total_click === null ? '0' : $results_total_click,
			];
		}

		/**
		 * Get file name from URL or file id
		 *
		 * @param string $url URL of file
		 * @param int $file_id File ID
		 *
		 * @return string File Name
		 */
		public function get_filename_from_url( $url, $file_id ) {
			$url_patterns = explode( '/', $url );
			$filename     = end( $url_patterns );
			if ( strlen( $filename ) > 0 ) {
				return $filename;
			}
			$file_url = wp_get_attachment_url( $file_id );

			return end( explode( '/', $file_url ) );
		}

		/**
		 * @param object $value
		 *
		 * @return object
		 */
		public function map_value_top_ten( $value ) {
			$value->edit_link = $this->get_edit_post_link_for_api( $value->id, '' );
			$value->filename  = $this->get_filename_from_url( $value->filename, $value->id );
			if ( property_exists( $value, 'url' ) ) {
				$value->full_url = $this->get_full_private_url( $value->url );
			}

			return $value;
		}

		/**
		 * @param string $id
		 * @param string $context
		 *
		 * @return string|void
		 */
		public function get_edit_post_link_for_api( $id, $context = 'display' ) {
			if ( ! $post = get_post( $id ) ) {
				return;
			}

			if ( 'revision' === $post->post_type ) {
				$action = '';
			} elseif ( 'display' == $context ) {
				$action = '&amp;action=edit';
			} else {
				$action = '&action=edit';
			}

			$post_type_object = get_post_type_object( $post->post_type );
			if ( ! $post_type_object ) {
				return;
			}

			if ( $post_type_object->_edit_link ) {
				return admin_url( sprintf( $post_type_object->_edit_link . $action, $post->ID ) );
			}

			return '';
		}

		/**
		 * @param string $url
		 *
		 * @return string
		 */
		private function get_full_private_url( $url ) {
			if ( method_exists( 'Pda_v3_Gold_Helper', 'get_private_url' ) ) {
				return Pda_v3_Gold_Helper::get_private_url( $url );
			}

			return $url;
		}
	}
}
