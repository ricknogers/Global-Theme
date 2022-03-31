<?php

namespace PDAGOLD\modules\Files;

class Loader {
	private $service;

	public function __construct() {
		$this->service = new Service();
	}

	public function register() {
		add_action( 'pda_after_un_protected', array( $this->service, 'handle_after_un_protected_file' ) );
		add_action( 'pda_before_sending_file', array( $this->service, 'maybe_open_encrypted_file' ), 10, 2 );
//		add_action( 'media_row_actions', array( $this->service, 'maybe_add_media_row_actions' ), 15, 2 );
		add_action( 'pda_show_status_file_in_pda_column', array( $this->service, 'maybe_show_encryption_status' ), 5 );

		$this->register_ajax();
	}

	public function register_ajax() {
		add_action( 'wp_ajax_pda_gold_encrypt_file', array( $this->service, 'encrypt_file_request' ) );
		add_action( 'wp_ajax_pda_gold_decrypt_file', array( $this->service, 'decrypt_file_request' ) );
	}
}
