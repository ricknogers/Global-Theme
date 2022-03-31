<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 5/17/18
 * Time: 10:02
 */

if ( ! class_exists( 'Prevent_Direct_Access_Gold_File_Handler' ) ) {

	class Prevent_Direct_Access_Gold_File_Handler {

		/**
		 * Move attachment file to protected DIR.
		 *
		 * @param int   $attachment_id The attachment ID.
		 * @param array $metadata The attachment's metadata.
		 *
		 * @return bool|WP_Error
		 * true: Success to move attachment file to the protected folder.
		 * false: Failed to move attachment file to the protected folder.
		 * WP_Error: error throw from move_attachment_to_protected function.
		 */
		public static function move_attachment_file( $attachment_id, $metadata = [] ) {
			$file          = get_post_meta( $attachment_id, '_wp_attached_file', true );
			$reldir        = self::check_rel_dir( dirname( $file ) );
			$protected_dir = path_join( self::mv_upload_dir(), $reldir );

			return self::move_attachment_to_protected( $attachment_id, $protected_dir, $metadata );
		}

		/**
		 * Move attachment from WordPress's folder to protected folder
		 *
		 * @param int     $attachment_id The Attachment ID.
		 * @param string  $protected_dir Protected DIR where placing the protected attachment.
		 * @param array   $meta_input    The attachment's meta data.
		 * @param boolean $is_unprotect  Is unprotect action.
		 *
		 * @return bool|WP_Error
		 * true: Success to move attachment to protected DIR.
		 * false: Failed to move attachment to protected DIR.
		 * WP_Error: Error happened when the attachment ID is not found and moving file failed.
		 */
		public static function move_attachment_to_protected( $attachment_id, $protected_dir, $meta_input = [], $is_unprotect = false ) {
			if ( 'attachment' !== get_post_type( $attachment_id ) ) {
				return new WP_Error(
					'not_attachment',
					sprintf(
						/* translators: %d Attachment ID */
						__( 'The post with ID: %d is not an attachment post type.', 'prevent-direct-access-gold' ),
						$attachment_id
					),
					array(
						'status' => 404,
					)
				);
			}

			if ( path_is_absolute( $protected_dir ) ) {
				return new WP_Error(
					'protected_dir_not_relative',
					sprintf(
						/* translators: %s The protected DIR */
						__( 'The new path provided: %s is absolute. The new path must be a path relative to the WP uploads directory.', 'prevent-direct-access-gold' ),
						$protected_dir
					),
					array(
						'status' => 404,
					)
				);
			}

			$meta = empty( $meta_input ) ? wp_get_attachment_metadata( $attachment_id ) : $meta_input;
			$meta = is_array( $meta ) ? $meta : array();

			$file = get_post_meta( $attachment_id, '_wp_attached_file', true );
			// If we can not get file by using WP get_post_meta, we will do it by ourselves.
			if ( empty( $file ) ) {
				$attached_file = PDA_v3_Gold_Repository::get_attached_file( $attachment_id );
				$file = isset( $attached_file->meta_value ) ? $attached_file->meta_value : false;
			}
			$backups = get_post_meta( $attachment_id, '_wp_attachment_backup_sizes', true );

			$upload_dir = wp_upload_dir();

			$old_dir = dirname( $file );
			if ( in_array( $old_dir, array( '\\', '/', '.' ), true ) ) {
				$old_dir = '';
			}

			if ( $protected_dir === $old_dir ) {
				return true;
			}

			$old_full_path       = path_join( $upload_dir['basedir'], $old_dir );
			$protected_full_path = path_join( $upload_dir['basedir'], $protected_dir );

			if ( ! wp_mkdir_p( $protected_full_path ) ) {
				return new WP_Error(
					'wp_mkdir_p_error',
					sprintf(
						/* translators: %s The protected full path */
						__( 'There was an error making or verifying the directory at: %s', 'prevent-direct-access-gold' ),
						$protected_full_path
					),
					array(
						'status' => 500,
					)
				);
			}

			// Get all files.
			$sizes = array();
			if ( array_key_exists( 'sizes', $meta ) ) {
				$sizes = self::get_files_from_meta( $meta['sizes'] );
			}
			$backup_sizes = self::get_files_from_meta( $backups );

			$original_images = array( wp_basename( $file ) );
			/**
			 * Support '-scaled' image for release version 5.3.
			 * Use array merge to avoid duplicate.
			 */
			if ( isset( $meta['original_image'] ) && self::is_allow_merge_scaled_image( $is_unprotect, $meta['original_image'], $protected_full_path ) ) {
				$original_images = array_merge( $original_images, array( $meta['original_image'] ) );
			}

			$new_basenames = array_merge(
				$original_images,
				$sizes,
				$backup_sizes
			);
			$old_basenames = $new_basenames;

			// Should use wp_basename to handle i18n string.
			$orig_basename = wp_basename( $file );
			if ( is_array( $backups ) && isset( $backups['full-orig'] ) ) {
				$orig_basename = $backups['full-orig']['file'];
			}

			$orig_filename = pathinfo( $orig_basename );
			$orig_filename = $orig_filename['filename'];

			$result        = self::resolve_name_conflict( $new_basenames, $protected_full_path, $orig_filename );
			$new_basenames = $result['new_basenames'];

			$move_result = self::rename_files( $old_basenames, $new_basenames, $old_full_path, $protected_full_path );
			$move_result = apply_filters(
				'pda_after_move_files',
				$move_result,
				$attachment_id,
				array(
					'new_basenames' => $new_basenames,
					'old_basenames' => $old_basenames,
				)
			);
			if ( is_wp_error( $move_result ) ) {
				return $move_result;
			}

			$base_file_name = 0;

			if ( empty( $protected_dir ) ) {
				$new_attached_file = $new_basenames[0];
			} else {
				$new_attached_file = path_join( $protected_dir, $new_basenames[0] );
			}

			if ( array_key_exists( 'file', $meta ) ) {
				$meta['file'] = $new_attached_file;
			}
			update_post_meta( $attachment_id, '_wp_attached_file', $new_attached_file );

			if ( $new_basenames[ $base_file_name ] != $old_basenames[ $base_file_name ] ) {
				$pattern       = $result['pattern'];
				$replace       = $result['replace'];
				$separator     = '#';
				$orig_basename = ltrim(
					str_replace( $pattern, $replace, $separator . $orig_basename ),
					$separator
				);
				$meta          = self::update_meta_sizes_file( $meta, $new_basenames );
				self::update_backup_files( $attachment_id, $backups, $new_basenames );
			}

			update_post_meta( $attachment_id, '_wp_attachment_metadata', $meta );
			$guid = path_join( $protected_full_path, $orig_basename );
			wp_update_post(
				array(
					'ID'   => $attachment_id,
					'guid' => $guid,
				)
			);

			return empty( $meta_input ) ? true : $meta;
		}

		/**
		 * Get the Protected Upload DIR.
		 *
		 * @param string $path The file path.
		 * @param bool   $in_url Is using in URL to add slash.
		 *
		 * @return string
		 */
		public static function mv_upload_dir( $path = '', $in_url = false ) {

			$dirpath  = $in_url ? '/' : '';
			$dirpath .= '_pda';
			$dirpath .= $path;

			return $dirpath;
		}

		/**
		 * Get files from metadata size.
		 *
		 * @param array $meta File metadata's size.
		 *
		 * @return array
		 */
		public static function get_files_from_meta( $meta ) {
			$files = array();
			if ( is_array( $meta ) ) {
				foreach ( $meta as $size ) {
					$files[] = $size['file'];
				}
			}

			return $files;
		}

		/**
		 * Resolving the name conflicts with new and old base names.
		 *
		 * @param array  $new_basenames        The new base names.
		 * @param string $protected_full_path  The protected full path.
		 * @param string $orig_file_name       The original file name.
		 *
		 * @return array
		 */
		public static function resolve_name_conflict( $new_basenames, $protected_full_path, $orig_file_name ) {
			$conflict     = true;
			$number       = 1;
			$separator    = '#';
			$med_filename = $orig_file_name;
			$pattern      = '';
			$replace      = '';
			// Set number < 500 to avoid infinite loop (Stress CPU).
			while ( $conflict && $number < 500 ) {
				$conflict = false;
				foreach ( $new_basenames as $basename ) {
					if ( is_file( path_join( $protected_full_path, $basename ) ) ) {
						$conflict = true;
						break;
					}
				}

				if ( $conflict ) {
					$new_filename = "$orig_file_name-$number";
					$number ++;
					$pattern       = "$separator$med_filename";
					$replace       = "$separator$new_filename";
					$new_basenames = explode(
						$separator,
						ltrim(
							str_replace( $pattern, $replace, $separator . implode( $separator, $new_basenames ) ),
							$separator
						)
					);

				}
			}


			return array(
				'new_basenames' => $new_basenames,
				'pattern'       => $pattern,
				'replace'       => $replace,
			);

		}

		/**
		 * Check old file to move file
		 *
		 * @param array  $old_base_names All base name file names including file containing sizes(ex: pic1.jpg, pic1-150x150.jpg, pic1-300x300.jpg).
		 * @param string $old_dir        The old dir path.
		 *
		 * @return WP_Error if isn't file.
		 */
		public static function check_old_files_before_move_file( $old_base_names, $old_dir ) {
			foreach ( $old_base_names as $base_name ) {
				$old_full_path = path_join( $old_dir, $base_name );
				if ( is_file( $old_full_path ) ) {
					continue;
				}

				return new WP_Error(
					'old_file_not_exist',
					sprintf(
						/* translators: %1$s file path */
						__( 'The file: %s does not exist', 'prevent-direct-access-gold' ),
						$old_full_path
					)
				);
			}
		}

		/**
		 * Move file to new folder.
		 *
		 * @param array  $old_basenames All old bases name.
		 * @param array  $new_basenames All new bases name.
		 * @param string $old_dir       Old dir path.
		 * @param string $protected_dir New dir path.
		 *
		 * @return WP_Error or move file successfully
		 * If move error => return WP_Error
		 * Else successfully
		 */
		public static function rename_files( $old_basenames, $new_basenames, $old_dir, $protected_dir ) {
			if ( ! is_dir( $protected_dir ) ) {
				return new WP_Error(
					'rename_failed',
					sprintf(
						/* translators: %1$s dir path */
						__( 'Directory %s is not existed.', 'prevent-direct-access-gold' ),
						$protected_dir
					)
				);
			}
			$unique_old_basenames = array_values( array_unique( $old_basenames ) );

			$old_files_valid = self::check_old_files_before_move_file( $unique_old_basenames, $old_dir );
			if ( is_wp_error( $old_files_valid ) ) {
				return $old_files_valid;
			}

			$unique_new_basenames = array_values( array_unique( $new_basenames ) );
			$i                    = count( $unique_old_basenames );
			while ( $i -- ) {
				$old_fullpath = path_join( $old_dir, $unique_old_basenames[ $i ] );
				$new_fullpath = path_join( $protected_dir, $unique_new_basenames[ $i ] );
				rename( $old_fullpath, $new_fullpath );
				// @codeCoverageIgnoreStart
				if ( ! is_file( $new_fullpath ) ) {
					return new WP_Error(
						'rename_failed',
						sprintf(
							/* translators: %1$s old path, %2$s new path */
							__( 'Rename failed when trying to move file from: %1$s, to: %2$s', 'prevent-direct-access-gold' ),
							$old_fullpath,
							$new_fullpath
						)
					);
				}
				// @codeCoverageIgnoreEnd
			}
		}

		/**
		 * Update meta sizes of file.
		 *
		 * @param array $meta          Metadata to update.
		 * @param array $new_basenames New base names.
		 *
		 * @return mixed The updated meta file sizes
		 */
		public static function update_meta_sizes_file( $meta, $new_basenames ) {
			if ( array_key_exists( 'sizes', $meta ) && is_array( $meta['sizes'] ) ) {
				$i = 0;

				foreach ( $meta['sizes'] as $size => $data ) {
					$meta['sizes'][ $size ]['file'] = $new_basenames[ ++ $i ];
				}
				error_log( "Metadata" );
				error_log( serialize( $meta ) );
			}

			return $meta;
		}

		/**
		 * Update backup files post meta.
		 *
		 * @param int   $attachment_id The attachment ID.
		 * @param array $backups The backup files.
		 * @param array $new_basenames The new base names.
		 */
		public static function update_backup_files( $attachment_id, $backups, $new_basenames ) {
			if ( is_array( $backups ) ) {
				$i                = 0;
				$l                = count( $backups );
				$new_backup_sizes = array_slice( $new_basenames, - $l, $l );

				foreach ( $backups as $size => $data ) {
					$backups[ $size ]['file'] = $new_backup_sizes[ $i ++ ];
				}
				update_post_meta( $attachment_id, '_wp_attachment_backup_sizes', $backups );
			}
		}

		/**
		 * Get attachment id from url
		 *
		 * @param array $file_info The file information including basename which called from pathinfo function.
		 *
		 * @return int > -1 if found attachment
		 */
		public static function get_attachment_id_from_url( $file_info ) {
			global $wpdb;
			$basename      = $file_info['basename'];
			$prepare       = $wpdb->prepare(
				"SELECT post_id, meta_value
                 FROM $wpdb->postmeta
                 WHERE meta_key = %s
                 	  AND meta_value LIKE %s
        		",
				'_wp_attachment_metadata',
				"%$basename%"
			);
			$attachments   = $wpdb->get_results( $prepare, ARRAY_A );
			$attachment_id = - 1;
			foreach ( $attachments as $attachment ) {
				$meta_value = unserialize( $attachment['meta_value'] );
				if ( isset( $meta_value['file'] ) ) {
					if ( ltrim( dirname( $meta_value['file'] ), '/' ) === ltrim( $file_info['dirname'], '/' ) ) {
						$attachment_id = $attachment['post_id'];
						break;
					}
				}
			}

			return $attachment_id;
		}

		/**
		 * Get attachment ID from the file path.
		 *
		 * @param string $file_path The file's path.
		 *
		 * @return int
		 * -1 if the attachment doesn't exist.
		 */
		public static function get_attachment_id_from_file_path( $file_path ) {
			global $wpdb;

			$path       = ltrim( $file_path, '/' );
			$prepare    = $wpdb->prepare(
				"SELECT post_id
                 FROM $wpdb->postmeta
                 WHERE meta_key = %s
                 	  AND meta_value = %s",
				'_wp_attached_file',
				"$path"
			);
			$attachment = $wpdb->get_row( $prepare );
			if ( is_null( $attachment ) ) {
				return - 1;
			}

			return $attachment->post_id;
		}

		/**
		 * Check relative directory
		 *
		 * @param string $reldir Relative directory.
		 *
		 * @return string
		 */
		public static function check_rel_dir( $reldir ) {
			if ( in_array( $reldir, array( '\\', '/', '.' ), true ) ) {
				$reldir = '';
			}

			return $reldir;
		}

		/**
		 * Fix original image existing on WordPress upload folder.
		 *
		 * @param boolean $is_unprotect_action  Is unprotect.
		 * @param string  $exclude_image        Image need to exclude.
		 * @param string  $wordpress_upload_dir WordPress upload dir.
		 *
		 * @return bool
		 */
		private static function is_allow_merge_scaled_image( $is_unprotect_action, $exclude_image, $wordpress_upload_dir ) {
			if ( ! $is_unprotect_action ) {
				return true;
			}
			$original_image_file = path_join( $wordpress_upload_dir, $exclude_image );

			return ! is_file( $original_image_file );
		}
	}
}
