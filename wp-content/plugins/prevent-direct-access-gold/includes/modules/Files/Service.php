<?php

namespace PDAGOLD\modules\Files;

class Service {
	private $logger;
	private $repo;
	private $func;

	public function __construct() {
		$this->logger = new \PDA_Logger();
		$this->repo   = new \PDA_v3_Gold_Repository();
		$this->func   = new \Pda_Gold_Functions();
	}

	/**
	 * Decrypt file after protected.
	 *
	 * @param integer $attachment_id Attachment ID.
	 */
	public function handle_after_un_protected_file( $attachment_id ) {
		try {
			$this->decrypt_file( $attachment_id );
		} catch ( \Exception $exception ) {
			$this->logger->notice( $exception->getMessage() );
		}
	}

	/**
	 * Open encrypted file by private link or protected link.
	 *
	 * @param string $url  Private link|Protected link.
	 * @param array  $data Attachment data.
	 */
	public function maybe_open_encrypted_file( $url, $data ) {
		if ( ! isset( $data['attachment_id'] ) ) {
			return;
		}
		$attachment_id = $data['attachment_id'];
		$link_type     = \Pda_v3_Gold_Helper::get( $data, 'link_type', \PDA_v3_Constants::PDA_ORIGINAL_LINK );

		try {
			$crypto = new Crypto();
		} catch ( \Exception $exception ) {
			$this->logger->notice( $exception->getMessage() );

			return;
		}

		// Only handle encrypted file.
		$encrypted = Util::is_file_encrypted( $attachment_id );
		if ( ! $encrypted ) {
			return;
		}

		$attached_file     = get_attached_file( $attachment_id );
		$is_force_download = false;
		if ( \PDA_v3_Constants::PDA_PRIVATE_LINK === $link_type ) {
			$is_force_download = $this->func->getSettings( \PDA_v3_Constants::FORCE_DOWNLOAD ) == "true";
		}

		try {
			$crypto->open_encrypted_file( $attached_file, $is_force_download );
		} catch ( \Exception $exception ) {
			$this->logger->notice( $exception->getMessage() );

			file_not_found();
			exit();
		}
		exit;
	}

	/**
	 * Encrypt file.
	 *
	 * @param string|integer $attachment_id Attachment ID.
	 *
	 * @throws \Exception
	 */
	public function encrypt_file( $attachment_id ) {
		// Does not handle unprotected file.
		if ( ! $this->repo->is_protected_file( $attachment_id ) ) {
			throw new \Exception( 'Oops! This file does not protected.' );
		}

		$crypto = new Crypto();

		$encrypted = Util::is_file_encrypted( $attachment_id );
		if ( $encrypted ) {
			throw new \Exception( 'Oops! This file is already encrypted.' );
		}

		// Don't encrypt if file was encrypted.
		$attached_file = get_attached_file( $attachment_id );
		if ( ! file_exists( $attached_file ) ) {
			throw new \Exception( 'Oops! This file does not exist in your server.' );
		}

		// Get file size and cropped to encrypt this.
		$file_paths = Util::get_attachment_file_paths( $attachment_id, false );
		if ( $file_paths ) {
			$file_paths   = array_values( $file_paths );
			$file_paths[] = $attached_file;
			$file_paths   = array_unique( $file_paths );

			foreach ( $file_paths as $file_path ) {
				$crypto->encrypt( $file_path, $file_path . '.enc' );
				rename( $file_path . '.enc', $file_path );
			}
		} else {
			$crypto->encrypt( $attached_file, $attached_file . '.enc' );
			rename( $attached_file . '.enc', $attached_file );
		}

		Util::update_file_encrypted_meta( $attachment_id, 1 );

		do_action( 'pda_after_encrypting_file', $attachment_id, $attached_file, $file_paths );
	}

	/**
	 * Decrypt file.
	 *
	 * @param integer $attachment_id Attachment ID.
	 *
	 * @throws \Exception
	 */
	public function decrypt_file( $attachment_id ) {
		$crypto = new Crypto();

		$attached_file = get_attached_file( $attachment_id );
		if ( ! file_exists( $attached_file ) ) {
			throw new \Exception( 'Oops! This file does not exist in your server.' );
		}

		// Don't decrypt if file was decrypted.
		$encrypted = Util::is_file_encrypted( $attachment_id );
		if ( ! $encrypted ) {
			throw new \Exception( 'Oops! This file is already decrypted.' );
		}

		// Get file size and cropped to decrypt this.
		$file_paths = Util::get_attachment_file_paths( $attachment_id, false );
		if ( $file_paths ) {
			$file_paths   = array_values( $file_paths );
			$file_paths[] = $attached_file;
			$file_paths   = array_unique( $file_paths );

			foreach ( $file_paths as $file_path ) {
				$crypto->decrypt( $file_path, $file_path . '.dec' );
				rename( $file_path . '.dec', $file_path );
			}
		} else {
			$crypto->decrypt( $attached_file, $attached_file . '.dec' );
			rename( $attached_file . '.dec', $attached_file );
		}

		Util::update_file_encrypted_meta( $attachment_id, false );

		do_action( 'pda_after_decrypting_file', $attachment_id, $attached_file, $file_paths );
	}

	/**
	 * Get encrypt button for row action.
	 *
	 * @param integer $attachment_id Attachment ID.
	 *
	 * @return false|string
	 */
	private function get_encrypt_btn( $attachment_id ) {
		$encrypt = __( 'Encrypt', 'prevent-direct-access-gold' );

		ob_start();
		?>
		<a class="pda-gold-crypto-btn" data-id="<?php echo $attachment_id; ?>" data-type="encrypt" style="cursor: pointer;"><?php echo $encrypt; ?></a>
		<?php

		return ob_get_clean();
	}

	/**
	 * Get decrypt button for row action.
	 *
	 * @param integer $attachment_id Attachment ID.
	 *
	 * @return false|string
	 */
	private function get_decrypt_btn( $attachment_id ) {
		$decrypt = __( 'Decrypt', 'prevent-direct-access-gold' );

		ob_start();
		?>
		<a data-id="<?php echo $attachment_id; ?>" data-type="decrypt" style="cursor: pointer;"
		   class="pda-gold-crypto-btn"><?php echo $decrypt; ?></a>
		<?php

		return ob_get_clean();
	}

	public function maybe_add_media_row_actions( $actions, $post ) {
		$post_id = $post->ID;

		if ( ! $this->repo->is_protected_file( $post_id ) ) {
			return $actions;
		}

		wp_enqueue_script(
			'pda_gold-ra-js',
			plugin_dir_url( __FILE__ ) . 'assets/script.js',
			array( 'jquery' ),
			PDA_GOLD_V3_VERSION,
			true
		);
		wp_localize_script(
			"pda_gold-ra-js",
			'pda_gold',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'pda-gold' ),
			)
		);

		if ( ! Util::is_file_encrypted( $post_id ) ) {
			$actions['pda_crypto'] = $this->get_encrypt_btn( $post_id );
		} else {
			$actions['pda_crypto'] = $this->get_decrypt_btn( $post_id );
		}

		return $actions;
	}

	/**
	 * Handle encrypt file request from client.
	 */
	public function encrypt_file_request() {
		$post_id = \Pda_v3_Gold_Helper::get( $_POST, 'post_id' );

		if ( !$post_id ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => 'Post does not exist.',
				),
				400
			);
			wp_die();
		}

		try {
			$this->encrypt_file( $post_id );

			wp_send_json(
				array(
					'success' => true,
					'message' => 'File encrypted successfully.',
				)
			);
			wp_die();
		} catch ( \Exception $exception ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => $exception->getMessage(),
				),
				400
			);
			wp_die();
		}
	}

	/**
	 * Handle decrypt file request from client.
	 */
	public function decrypt_file_request() {
		$post_id = \Pda_v3_Gold_Helper::get( $_POST, 'post_id' );

		if ( !$post_id ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => 'Post does not exist',
				),
				400
			);
			wp_die();
		}

		try {
			$this->decrypt_file( $post_id );

			wp_send_json(
				array(
					'success' => true,
					'message' => 'File decrypted successfully.',
				)
			);
			wp_die();
		} catch ( \Exception $exception ) {
			wp_send_json(
				array(
					'success' => false,
					'message' => $exception->getMessage(),
				),
				400
			);
			wp_die();
		}
	}

	/**
	 * Show encryption status.
	 *
	 * @param integer $attachment_id Attachment ID.
	 */
	public function maybe_show_encryption_status( $attachment_id ) {
		$is_encrypted = Util::is_file_encrypted( $attachment_id );

		?>
		<span
			<?php echo ! $is_encrypted ? 'style="display: none; margin-left: 0; margin-right: 0.5rem;"' : 'style="margin-left: 0; margin-right: 0.5rem;"';?>
			id="pda-v3-encryption_<?php echo esc_attr( $attachment_id ); ?>"
			class="encryption-status"
			title="File encrypted"
		>
            <i class="fa fa-lock" aria-hidden="true"></i>&nbsp;encrypted
        </span>
		<?php
	}
}

