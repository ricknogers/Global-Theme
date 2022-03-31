<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 3/16/20
 * Time: 14:41
 */

if ( class_exists( 'PDA_WPML' ) ) {
	return;
}

/**
 * WPML integration
 *
 * Class PDA_WPML
 */
class PDA_WPML {
	/**
	 * Database repository
	 *
	 * @var PDA_v3_Gold_Repository.
	 */
	private $repo;

	const I18N_WARNING_DISMISS = 'pda_i18n_warning_dismiss';

	public function __construct( $repo = null ) {
		if ( is_null( $repo ) ) {
			$this->repo = new PDA_v3_Gold_Repository();
		} else {
			$this->repo = $repo;
		}
	}

	/**
	 * Is installed?
	 *
	 * @return bool
	 */
	public function is_installed() {
		return class_exists( 'SitePress' );
	}

	/**
	 * Init integration
	 */
	public function init() {
		add_action( 'wpml_media_create_duplicate_attachment', array( $this, 'duplicate_protected_item' ), 10, 2 );
		add_action( 'pda_after_protect_file_when_upload', array( $this, 'after_protect_file' ), 9, 1 );
		add_action( 'pda_after_protected', array( $this, 'after_protect_file' ), 9, 1 );
		add_action( 'pda_after_un_protected', array( $this, 'after_unprotect_file' ), 9, 1 );
		add_action( 'pda_handle_attachment_id', array( $this, 'handle_attachment_id' ), 9, 1 );
		add_action( 'pda_before_handle_role_protection', array( $this, 'show_protection_features' ), 10, 1 );
		add_action( 'admin_notices', array( $this, 'show_i18n_notice' ), 20 );
	}

	/**
	 * WPML fires an action after each attachment is duplicated. Need to duplicate the protection data also.
	 *
	 * @param int $attachment_id     The attachment's ID to duplicate.
	 * @param int $new_attachment_id The new attachment's ID after duplicating.
	 */
	public function duplicate_protected_item( $attachment_id, $new_attachment_id ) {
		$is_old_item_protected = $this->repo->is_protected_file( $attachment_id );
		if ( ! $is_old_item_protected ) {
			return;
		}

		$result = $this->protect_duplicated_file( $new_attachment_id );
		do_action( 'pda_after_protect_duplicated_wmpl_file', $result, $new_attachment_id );
	}

	/**
	 * When finish protecting, make sure any duplication is also protected.
	 *
	 * @param int $attachment_id The attachment ID.
	 */
	public function after_protect_file( $attachment_id ) {
		$this->protect_duplicated_files( $attachment_id );
	}

	/**
	 * After un-protecting file.
	 *
	 * @param int $attachment_id The attachment ID.
	 */
	public function after_unprotect_file( $attachment_id ) {
		$file_path = path_join(
			'_pda',
			get_post_meta( $attachment_id, '_wp_attached_file', true )
		);
		$results   = $this->fetch_duplicated_attachment( $file_path, $attachment_id );
		foreach ( $results as $result ) {
			$this->duplicate_post_meta_data( $attachment_id, $result->post_id );
			$this->unprotect_duplicated_file( $result->post_id );
		}
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
		global $sitepress;

		if ( ! isset( $sitepress ) ) {
			return $attachment_id;
		}

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
	 * Show protection features only in default lang. Only show then when user is currently in default lang.
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
		global $sitepress;
		$current_language = $sitepress->get_current_language();
		$default_language = $sitepress->get_default_language();

		return $current_language === $default_language;
	}

	/**
	 * Check whether the current attachment language is default one.
	 *
	 * @param int $attachment_id The attachment ID.
	 *
	 * @return bool
	 */
	protected function is_default_language( $attachment_id ) {
		global $sitepress;
		$default_language = $sitepress->get_default_language();
		$att_language     = $sitepress->get_language_for_element( $attachment_id, 'post_attachment' );

		return $default_language === $att_language;
	}

	/**
	 * Protect the duplicated files after original file is protected.
	 *
	 * @param int $attachment_id The original attachment ID.
	 */
	protected function protect_duplicated_files( $attachment_id ) {
		// Apply regular expression here.
		// Since newest version of WPML(4.4.2), it automatically duplicated post meta. No need to remove _pda.
		$file_path = get_post_meta( $attachment_id, '_wp_attached_file', true );
		$results   = $this->fetch_duplicated_attachment( $file_path, $attachment_id );
		if ( empty( $results ) ) {
			$pda_file_path = str_replace( '_pda/', '', $file_path );
			$results   = $this->fetch_duplicated_attachment( $pda_file_path, $attachment_id );
		}

		foreach ( $results as $result ) {
			$this->duplicate_post_meta_data( $attachment_id, $result->post_id );
			$this->protect_duplicated_file( $result->post_id );
		}
	}

	/**
	 * Fetch the duplicated attachment from DB.
	 *
	 * @param string $file_path     The same file path (_wp_attached_file) between duplicated attachments.
	 * @param int    $attachment_id The attachment ID.
	 *
	 * @return mixed
	 */
	protected function fetch_duplicated_attachment( $file_path, $attachment_id ) {
		global $wpdb;
		$sql = $wpdb->prepare(
			"
				SELECT m.post_id
				FROM  $wpdb->postmeta AS m
				LEFT JOIN " . $wpdb->posts . " AS p ON m.post_id = p.ID AND p.`post_type` = 'attachment'
				WHERE m.meta_key = '_wp_attached_file'
				AND m.meta_value = %s
				AND m.post_id != %d
				;
			",
			$file_path,
			$attachment_id
		);

		$results = $wpdb->get_results( $sql ); // phpcs:ignore

		return $results;
	}

	/**
	 * Protect duplicated file
	 *
	 * @param int $attachment_id The attachment ID.
	 *
	 * @return mixed
	 */
	protected function protect_duplicated_file( $attachment_id ) {
		return $this->repo->updated_file_protection( $attachment_id, true );
	}

	/**
	 * Un-protect the duplicated file.
	 *
	 * @param int $attachment_id The attachment ID.
	 *
	 * @return mixed
	 */
	protected function unprotect_duplicated_file( $attachment_id ) {
		$result = $this->repo->updated_file_protection( $attachment_id, false );

		return $result;
	}

	/**
	 * Duplicate post meta data from the original file.
	 *
	 * @param  int $attachment_id            The original attachment's ID.
	 * @param  int $duplicated_attachment_id The duplicated attachment's ID.
	 */
	protected function duplicate_post_meta_data( $attachment_id, $duplicated_attachment_id ) {
		foreach ( array( '_wp_attachment_metadata', '_wp_attached_file' ) as $meta_key ) {
			$source_meta_value = get_post_meta( $attachment_id, $meta_key, true );
			update_post_meta( $duplicated_attachment_id, $meta_key, $source_meta_value );
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

	/**
	 * I18n html notice.
	 */
	public function echo_18n_notice() {
		?>
		<div data-pdaname="<?php echo self::I18N_WARNING_DISMISS; ?>"
		     class="notice pda-notice notice-warning is-dismissible">
			<p>
				<b><?php echo "Prevent Direct Access Gold: "; ?></b> The File Protection Configuration isn't available under sub-language page created by <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/docs/wpml-compatibility/#logic">WPML</a> or <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/docs/polylang-compatibility/#logic">Polylang</a>. Switch to the main language page to use this feature.
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text">Dismiss this notice.</span>
				</button>
			</p>
		</div>
		<?php
	}

	/**
	 * Display i18n notice with conditional.
	 *
	 * @return bool
	 */
	public function maybe_show_i18n_notice() {
		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}

		$screen              = get_current_screen();
		$default_show_screen = array(
			'upload',
		);
		if ( ! in_array( $screen->id, $default_show_screen, true ) ) {
			return false;
		}
		if ( $this->is_on_default_language() ) {
			return false;
		}

		if ( isset( $_COOKIE[ self::I18N_WARNING_DISMISS ] ) ) {
			return false;
		}

		global $pda_load_notice_dismiss_script;
		$pda_load_notice_dismiss_script = true;

		return true;
	}
}
