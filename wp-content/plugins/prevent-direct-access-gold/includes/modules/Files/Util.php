<?php

namespace PDAGOLD\modules\Files;

class Util {
	const ENCRYPTED_META = '_pda_encryption';

	/**
	 * Get file paths for all attachment versions.
	 *
	 * @param int        $attachment_id
	 * @param bool       $exists_locally
	 * @param array|bool $meta
	 * @param bool       $include_backups
	 *
	 * @return array
	 */
	public static function get_attachment_file_paths( $attachment_id, $exists_locally = true, $meta = false, $include_backups = true ) {
		$file_path = get_attached_file( $attachment_id, true );
		$paths     = array();

		if ( ! $meta ) {
			$meta = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );
		}

		if ( is_wp_error( $meta ) ) {
			return $paths;
		}

		$file_name = wp_basename( $file_path );

		// If file edited, current file name might be different.
		if ( isset( $meta['file'] ) ) {
			$paths['file'] = str_replace( $file_name, wp_basename( $meta['file'] ), $file_path );
		}

		// Thumb
		if ( isset( $meta['thumb'] ) ) {
			$paths['thumb'] = str_replace( $file_name, $meta['thumb'], $file_path );
		}

		// Original Image (when large image scaled down to threshold size and used as "full").
		if ( isset( $meta['original_image'] ) ) {
			$paths['original_image'] = str_replace( $file_name, $meta['original_image'], $file_path );
		}

		// Sizes
		if ( isset( $meta['sizes'] ) ) {
			foreach ( $meta['sizes'] as $size => $file ) {
				if ( isset( $file['file'] ) ) {
					$paths[ $size ] = str_replace( $file_name, $file['file'], $file_path );
				}
			}
		}

		$backups = get_post_meta( $attachment_id, '_wp_attachment_backup_sizes', true );

		// Backups
		if ( $include_backups && is_array( $backups ) ) {
			foreach ( $backups as $size => $file ) {
				if ( isset( $file['file'] ) ) {
					$paths[ $size ] = str_replace( $file_name, $file['file'], $file_path );
				}
			}
		}

		// Allow other processes to add files to be uploaded
		$paths = apply_filters( 'pda_s3_attachment_file_paths', $paths, $attachment_id, $meta );

		// Remove paths that don't exist
		if ( $exists_locally ) {
			foreach ( $paths as $key => $path ) {
				if ( ! file_exists( $path ) ) {
					unset( $paths[ $key ] );
				}
			}
		}

		return $paths;
	}

	public static function is_octet_stream( $mime ) {
		return strpos( $mime, 'zip' );
	}

	public static function is_file_encrypted( $attachment_id ) {
		$encrypted = get_post_meta( $attachment_id, self::ENCRYPTED_META, true );

		return wp_validate_boolean( $encrypted );
	}

	public static function update_file_encrypted_meta( $attachment_id, $value) {
		return update_post_meta( $attachment_id, self::ENCRYPTED_META, $value );
	}

	/**
	 * @param $file
	 * @param $mime_type
	 *
	 * @return bool
	 */
	public static function is_attachment_file( $file, $mime_type ) {
		return \Pda_v3_Gold_Helper::is_image( $file, $mime_type )
		       || \Pda_v3_Gold_Helper::is_pdf( $mime_type )
		       || \Pda_v3_Gold_Helper::is_video( $mime_type )
		       || \Pda_v3_Gold_Helper::is_html( $mime_type )
		       || \Pda_v3_Gold_Helper::is_audio( $mime_type );
	}

}
