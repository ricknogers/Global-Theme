<?php
/**
 * User: gaupoit
 * Date: 8/28/18
 * Time: 10:04
 *
 * @package pda_services
 */

if ( ! class_exists( 'PDA_Private_Link_Services' ) ) {
	/**
	 * Service class that containing the helper functions in order to interact with the private links.
	 *
	 * Class PDA_Private_Link_Services
	 */
	class PDA_Private_Link_Services {
		/**
		 * Create new private link for the protected attachment file.
		 *
		 * Code example: https://gist.github.com/bwps/577a06d2f1f6a063a2e856da167fdc18
		 *
		 * @param int   $attachment_id The attachment's id.
		 *
		 * @param array $data          (optional) Array structure to define additional information for the private links.
		 *
		 * $data = [
		 *
		 *  'expired_days' => (integer) The number of days the private link will expire from the created day.
		 *
		 *  'limit_downloads' => (integer) The max number of times that link can be downloaded.
		 *
		 * ]
		 *
		 * Example:
		 *
		 * $data = [
		 *
		 *  'expired_days' => 5,
		 *
		 *  'limit_downloads' => 3,
		 *
		 * ]
		 *
		 *
		 * Happy coding.
		 *
		 * @return string Return empty string if the attachment file is not protected, otherwise return the new private link.
		 */
		public static function create_private_link( $attachment_id, $data, $use_raw_url = true ) {
			$private_link = '';
			$repo         = new PDA_v3_Gold_Repository();
			if ( ! $repo->is_protected_file( $attachment_id ) ) {
				return $private_link;
			}

			if ( isset( $data['url'] ) ) {
				$new_private_link = $data['url'];
			} else {
				$new_private_link = Pda_v3_Gold_Helper::generate_unique_string();
			}

			$file_info        = array(
				'post_id'         => $attachment_id,
				'is_prevented'    => true,
				'limit_downloads' => isset( $data['limit_downloads'] ) ? $data['limit_downloads'] : null,
				'expired_date'    => isset( $data['expired_days'] ) ? Pda_v3_Gold_Helper::get_expired_time_stamp( $data['expired_days'] ) : null,
				'url'             => $new_private_link,
				'type'            => isset( $data['type'] ) ? $data['type'] : '',
			);
			$result           = $repo->create_private_link( $file_info );
			if ( $result > 0 ) {
				$private_link = Pda_v3_Gold_Helper::get_private_url( $new_private_link, $use_raw_url );
			}

			return $private_link;
		}

		/**
		 * Protect attachment file
		 *
		 * Code example: https://gist.github.com/bwps/9fee59a084f2c1cccba45a97ac335d0c
		 *
		 * @param int $attachment_id The attachment's id.
		 *
		 * @return bool|WP_Error
		 */
		public static function protect_file( $attachment_id ) {
			do_action( PDA_Hooks::PDA_HOOK_BEFORE_PROTECT_FILE, $attachment_id );

			$file = get_post_meta( $attachment_id, '_wp_attached_file', true );
			if ( 0 === stripos( $file, Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir( '/' ) ) ) {
				return new WP_Error(
					'protected_file_existed',
					sprintf(
						__( 'This file is already protected. Please reload your page.', 'prevent-direct-access-gold' ),
						$file
					),
					array( 'status' => 500 )
				);
			}

			$reldir = dirname( $file );
			if ( in_array( $reldir, array( '\\', '/', '.' ), true ) ) {
				$reldir = '';
			}
			$protected_dir = path_join( Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir(), $reldir );
			$move_result   = Prevent_Direct_Access_Gold_File_Handler::move_attachment_to_protected( $attachment_id, $protected_dir );

			if ( is_wp_error( $move_result ) ) {
				return $move_result;
			} else {
				$repo = new PDA_v3_Gold_Repository();
				$repo->updated_file_protection( $attachment_id, true );
				do_action( PDA_Hooks::PDA_HOOK_AFTER_PROTECT_FILE, $attachment_id );
				$service = new PDA_Services();
				$service->auto_create_new_private_link(
					array(
						'id' => $attachment_id,
					)
				);

				return true;
			}

		}

		/**
		 * Set permission for file is protected
		 *
		 * @param int   $attachment_id The attachment's id.
		 * @param array $fap           Permission.
		 *
		 * @return bool
		 */
		public static function pda_set_permission_for_file_protect( $attachment_id, $fap = array() ) {
			$repo_v3      = new PDA_v3_Gold_Repository();
			$is_protected = $repo_v3->is_protected_file( $attachment_id );
			if ( empty( $fap ) || ! $is_protected ) {
				return false;
			}

			if ( array_key_exists( 'type', $fap ) && ! empty( $fap['type'] ) ) {
				if ( - 1 === Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) ) {
					$result = apply_filters( 'pda_ip_block_handle_set_fap', $attachment_id, $fap );

					return true === $result;
				} else {
					return update_post_meta( $attachment_id, PDA_v3_Constants::$pda_meta_key_user_roles, $fap['type'] );
				}
			}

			return false;
		}

	}
}
