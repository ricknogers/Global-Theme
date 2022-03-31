<?php

namespace PDAGOLD\modules\Files;

class Crypto {
	const FILE_ENCRYPTION_BLOCKS = 255;
	protected $key;
	protected $cipher;

	/**
	 * Crypto constructor.
	 *
	 * @param string $cipher
	 *
	 * @throws \Exception
	 */
	public function __construct( $cipher = 'AES-128-CBC' ) {
		if ( ! function_exists( 'openssl_encrypt' ) ) {
			throw new \Exception( 'Openssl does not supported.' );
		}
		$key = $this->get_key();

		if ( static::supported( $key, $cipher ) ) {
			$this->key    = $key;
			$this->cipher = $cipher;
		} else {
			throw new \Exception( 'Encryption key is wrong or deleted.' );
		}
	}

	/**
	 * @return string
	 */
	public function get_key() {
		return get_option( 'pda_encryption_key' );
	}

	public static function supported( $key, $cipher ) {
		$length = mb_strlen( $key, '8bit' );

		return ( $cipher === 'AES-128-CBC' && $length === 16 ) ||
		       ( $cipher === 'AES-256-CBC' && $length === 32 );
	}

	/**
	 * Create a encrypted file.
	 *
	 * @param string $source Source file.
	 * @param string $dest   Destination file.
	 *
	 * @return bool
	 * @throws \Exception
	 */
	public function encrypt( $source, $dest ) {
		$iv = openssl_random_pseudo_bytes( 16 );

		if ( $fp_out = fopen( $dest, 'w' ) ) {
			// Put the initialzation vector to the beginning of the file
			fwrite( $fp_out, $iv );
			if ( $fpIn = fopen( $source, 'rb' ) ) {
				while ( ! feof( $fpIn ) ) {
					$plaintext  = fread( $fpIn, 16 * self::FILE_ENCRYPTION_BLOCKS );
					$ciphertext = openssl_encrypt( $plaintext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv );
					// Use the first 16 bytes of the ciphertext as the next initialization vector
					$iv = substr( $ciphertext, 0, 16 );
					fwrite( $fp_out, $ciphertext );
				}
				fclose( $fpIn );
			} else {
				throw new \Exception( 'Could not read source file.' );
			}
			fclose( $fp_out );
		} else {
			throw new \Exception( 'Could not write destination file.' );
		}

		return true;
	}


	/**
	 * Decrypt the passed file and saves the result in a new file, removing the
	 * last 4 characters from file name.
	 *
	 * @param string $source Path to file that should be decrypted
	 * @param bool   $dest Destination of file.
	 *
	 * @return string|boolean  Returns the file name that has been created or FALSE if an error occured
	 *
	 * @throws \Exception
	 */
	public function decrypt( $source, $dest = false ) {
		if ( $dest ) {
			$fp_out = fopen( $dest, 'w' );
		} else {
			$fp_out = tmpfile();
		}

		if ( $fp_in = fopen( $source, 'rb' ) ) {
			// Get the initialzation vector from the beginning of the file
			$iv = fread( $fp_in, 16 );
			while ( ! feof( $fp_in ) ) {
				$ciphertext = fread( $fp_in, 16 * ( self::FILE_ENCRYPTION_BLOCKS + 1 ) ); // we have to read one block more for decrypting than for encrypting
				$plaintext  = openssl_decrypt( $ciphertext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv );
				// Use the first 16 bytes of the ciphertext as the next initialization vector
				$iv = substr( $ciphertext, 0, 16 );
				fwrite( $fp_out, $plaintext );
			}
			fclose( $fp_in );
		} else {
			throw new \Exception( 'Could not read source file.' );
		}

		if ( $dest ) {
			fclose( $fp_out );

			return true;
		}

		return $fp_out;
	}

	/**
	 * Open encrypted file.
	 *
	 * @param string $file_path         File path.
	 * @param false  $is_force_download Is allow download file.
	 *
	 * @throws \Exception
	 */
	public function open_encrypted_file( $file_path, $is_force_download = false ) {
		if ( ! file_exists( $file_path ) ) {
			throw new \Exception( 'File does not exist.' );
		}

		$decrypted_file      = $this->decrypt( $file_path );
		$decrypted_file_path = stream_get_meta_data( $decrypted_file )['uri'];

		rewind( $decrypted_file );

		$filesize = filesize( $decrypted_file_path );

		header( 'Connection: Keep-Alive' );
		header( 'Expires: 0' );
		header( 'X-Robots-Tag: none' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' ); // HTTP 1.1.
		header( 'Pragma: no-cache' ); // HTTP 1.0.
		header( 'Content-Length: ' . $filesize );
		header( 'PDA: Encrypted' );

		$file_name = wp_basename( $file_path );
		$file_type = wp_check_filetype( $file_name );
		$mime_type = $file_type['type'];

		if ( ! $is_force_download && Util::is_attachment_file( $file_name, $mime_type ) ) {
			header( "Content-Disposition: inline; filename= " . $file_name );
		} else {
			header( "Content-Disposition: attachment; filename= " . $file_name );
		}

		if ( Util::is_octet_stream( $mime_type ) ) {
			header( 'Content-Type: application/octet-stream' );
		} else {
			header( "Content-Type: {$file_type['type']}" );
		}

		$output_stream = fopen( 'php://output', 'wb' );

		stream_copy_to_stream( $decrypted_file, $output_stream );

		fclose( $decrypted_file );
		fclose( $output_stream );
		exit;
	}
}

