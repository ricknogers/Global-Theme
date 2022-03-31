<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 3/16/20
 * Time: 14:41
 */

if ( class_exists( 'PDA_Polylang' ) || ! class_exists( 'PDA_WPML' ) ) {
	return;
}

/**
 * Polylang integration
 *
 * Class PDA_WPML
 */
class PDA_Polylang extends PDA_WPML {
	public function __construct( $repo = null ) {
		parent::__construct( $repo );
	}

	/**
	 * Is installed?
	 *
	 * @return bool
	 */
	public function is_installed() {
		return class_exists( 'Polylang' );
	}

	/**
	 * Init integration
	 */
	public function init() {
		add_action( 'pll_translate_media', array( $this, 'duplicate_protected_item' ), 10, 3 );
		add_action( 'pda_after_protect_file_when_upload', array( $this, 'after_protect_file' ), 9, 1 );
		add_action( 'pda_after_protected', array( $this, 'after_protect_file' ), 9, 1 );
		add_action( 'pda_after_un_protected', array( $this, 'after_unprotect_file' ), 9, 1 );
		add_filter( 'pda_handle_attachment_id', array( $this, 'handle_attachment_id' ), 9, 1 );
		add_action( 'pda_before_handle_role_protection', array( $this, 'show_protection_features' ), 10, 1 );
		add_action( 'admin_notices', array( $this, 'show_i18n_notice' ), 20 );
	}

	/**
	 * Handle attachment ID by comparing the language.
	 * Compare the attachment language with current on. Otherwise fetch duplicated files to find the post langue equals to current one.
	 *
	 * @param int $attachment_id The attachment ID.
	 *
	 * @return int
	 */
	public function handle_attachment_id( $attachment_id ) {
		if ( $this->is_default_language( $attachment_id ) ) {
			return $attachment_id;
		}

		// Find the duplicated attachments to compare with default language.
		$file_path = get_post_meta( $attachment_id, '_wp_attached_file', true );
		$results   = $this->fetch_duplicated_attachment( $file_path, $attachment_id );
		foreach ( $results as $result ) {
			if ( $this->is_default_language( $result->post_id ) ) {
				return $result->post_id;
			}
		}

		return $attachment_id;
	}

	/**
	 * Only show protection features in default lang.
	 *
	 * @return bool
	 */
	public function show_protection_features() {
		return $this->is_on_default_language();
	}

	/**
	 * Check whether user is currently on default language.
	 *
	 * @return bool
	 */
	public function is_on_default_language() {
		if ( ! function_exists( 'pll_default_language' ) || ! function_exists( 'pll_current_language' ) ) {
			return false;
		}

		return pll_default_language() === pll_current_language();
	}

	/**
	 * Check whether the current attachment language is default one.
	 *
	 * @param int $attachment_id The attachment ID.
	 *
	 * @return bool
	 */
	protected function is_default_language( $attachment_id ) {
		if ( ! function_exists( 'pll_default_language' ) || ! function_exists( 'pll_get_post_language' ) ) {
			return false;
		}

		return pll_default_language() === pll_get_post_language( $attachment_id );
	}

	/**
	 * Protect the duplicated files after original file is protected.
	 *
	 * @param int $attachment_id The original attachment ID.
	 */
	protected function protect_duplicated_files( $attachment_id ) {
		// Apply regular expression here.
		$file_path = get_post_meta( $attachment_id, '_wp_attached_file', true );
		$results   = $this->fetch_duplicated_attachment( $file_path, $attachment_id );
		foreach ( $results as $result ) {
			$this->duplicate_post_meta_data( $attachment_id, $result->post_id );
			$this->protect_duplicated_file( $result->post_id );
		}
	}

	/**
	 * After un-protecting file.
	 *
	 * @param int $attachment_id The attachment ID.
	 */
	public function after_unprotect_file( $attachment_id ) {
		$file_path = get_post_meta( $attachment_id, '_wp_attached_file', true );
		$results   = $this->fetch_duplicated_attachment( $file_path, $attachment_id );
		foreach ( $results as $result ) {
			$this->duplicate_post_meta_data( $attachment_id, $result->post_id );
			$this->unprotect_duplicated_file( $result->post_id );
		}
	}

	/**
	 * Show i18n notice message in sub-language page.
	 */
	public function show_i18n_notice() {
		if ( ! $this->maybe_show_i18n_notice() ) {
			return;
		}

		$this->echo_18n_notice();
	}
}
