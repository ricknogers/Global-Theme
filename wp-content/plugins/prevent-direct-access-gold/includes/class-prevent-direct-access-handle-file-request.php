<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 5/18/18
 * Time: 13:33
 */

/**
 * Handle to the file to download (eg: '/_pda/2018/05/sample-test-150x150.jpg')
 *
 * @param string $file_path File Path.
 * @param string $action    Action.
 *
 * @since 3.x.x Init function.
 * @since 3.1.4 Check file type before get post ID via attachment URL.
 */
function pda_v3_handle_protected_file_request( $file_path, $action = '' ) {
	$pda_helper = new Pda_v3_Gold_Helper();

	do_action( PDA_Private_Hooks::PDA_HOOK_CUSTOM_HANDLE_PROTECTED_FILE, $file_path );
	$logger     = new PDA_Logger();
	$upload_dir = wp_upload_dir();

	// Remove the query param.
	$file_path = strtok( $file_path, '?' );

	$file_info = pathinfo( $file_path );

	$extension = wp_check_filetype( $file_path );
	if ( false === $extension['type'] || false === strpos( $extension['type'], 'image' ) ) {
		$attachment_id = attachment_url_to_postid( $upload_dir['baseurl'] . $file_path );
	} else {
		$attachment    = $pda_helper->attachment_image_url_to_post( $upload_dir['baseurl'], $file_path );
		$attachment_id = empty( $attachment ) ? false : (int) $attachment->post_id;
	}

	/**
	 * Fire hook that can modify the attachment ID.
	 *
	 * @since Version 3.1.6
	 */
	$attachment_id = apply_filters( 'pda_handle_attachment_id', $attachment_id, $file_path, $extension );
	// 404 when attachment_id is not exist.
	if ( ! $attachment_id ) {
		file_not_found();
	}

	$file      = strtok( rtrim( $upload_dir['basedir'], '/' ) . str_replace( '..', '', $file_path ), '?' );
	$mime_type = $pda_helper->pda_mime_content_type( $file );
	if ( ! $mime_type ) {
		forbidden();
	}
	$data_original_link = array(
		'user_id'       => get_current_user_id(),
		'link_id'       => $attachment_id,
		'link_type'     => PDA_v3_Constants::PDA_ORIGINAL_LINK,
		'mime_type'     => $mime_type,
		'file'          => $file,
		'attachment_id' => $attachment_id,
	);
	$logger->info( sprintf( "Get protected file %s", $file ) );
	if ( ! is_file( $file ) ) {
		$logger->info( sprintf( "File %s doesn't exist!", $file ) );
		file_not_found();
	}


	$handled = apply_filters( PDA_Private_Hooks::PDA_HOOK_AFTER_CHECK_FILE_EXIST, false, $attachment_id );
	if ( $handled ) {
		do_action( PDA_Private_Hooks::PDA_HOOK_BEFORE_SENDING_PROTECTED_LINK, $data_original_link );
		send_file_to_client( $mime_type, $file, $data_original_link );
	}

	//TODO: need to check the logic between multi-site and FAP
	handle_multisite_direct_access( $mime_type, $file, $data_original_link );
	if ( 0 === strpos( $file_info['dirname'] . '/', Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir( '/', true ) ) ) {
		$repo              = new PDA_v3_Gold_Repository();
		$is_protected_file = $repo->is_protected_file( $attachment_id );
		$logger->info( sprintf( "File protection status: %s", $is_protected_file ) );
		if ( $is_protected_file ) {
			// Check file access permission before send file to client.
			$gold_function = new Pda_Gold_Functions();
			$fap_type      = '';
			if ( $gold_function->check_file_access_permission_for_post( $attachment_id, $fap_type ) ) {
				unset_login_form_cookie();
				do_action( PDA_Private_Hooks::PDA_HOOK_BEFORE_SENDING_PROTECTED_LINK, $data_original_link );
				send_file_to_client( $mime_type, $file, $data_original_link );
			} else {
				// FAP cannot pass.
				$login_page = apply_filters( 'pda_gold_redirect_to_login_page', $attachment_id );
				$no_one     = 'blank';
				// Will show login form if
				// 1. FAP type is not no one
				// 2. Filter returns a redirect URL
				// 3. Filter do not return a attachment ID.
				if ( $no_one !== $fap_type && $login_page !== $attachment_id && false !== $login_page ) {
					wp_safe_redirect( $login_page );

					return;
				} else {
					unset_login_form_cookie();
					file_not_found( '', $data_original_link );
					exit();
				}
			}
		}
	}

	do_action( PDA_Private_Hooks::PDA_HOOK_BEFORE_SENDING_PROTECTED_LINK, $data_original_link );
	send_file_to_client( $mime_type, $file, $data_original_link );
}

/**
 * Un-set login form cookie.
 */
function unset_login_form_cookie() {
	if ( isset( $_COOKIE[ PDA_v3_Constants::PDA_GOLD_LOGIN_PAGE_COOKIE ] ) ) {
		unset( $_COOKIE[ PDA_v3_Constants::PDA_GOLD_LOGIN_PAGE_COOKIE ] );
		setcookie( PDA_v3_Constants::PDA_GOLD_LOGIN_PAGE_COOKIE, '', time() - 3600, '/' );
	}
}

/**
 * @param $private_uri
 */
function pda_v3_handle_private_request( $private_uri ) {
	$logger = new PDA_Logger();

	$repo = new PDA_v3_Gold_Repository();
	$post = $repo->get_post_id_by_private_uri( $private_uri );

	$logger->info( sprintf( "The post: %s", wp_json_encode( $post ) ) );

	if ( ! isset( $post ) ) {
		file_not_found();
		exit();
	}

	$advance_file = $repo->get_advance_file_by_url( $private_uri );
	if ( ! isset( $advance_file ) ) {
		file_not_found();
		exit();
	}

	if ( is_under_limited_downloads( $advance_file ) || is_expired( $advance_file ) ) {
		file_not_found();
		exit();
	}

	$attachment_id = $post->post_id;
	$file_path     = '/' . get_post_meta( $attachment_id, '_wp_attached_file', true );
	$file_info     = pathinfo( $file_path );
	if ( 0 !== strpos( $file_info['dirname'] . '/', Prevent_Direct_Access_Gold_File_Handler::mv_upload_dir( '/', true ) ) ) {
		file_not_found();
	}

	$logger->info( sprintf( "The advance file: %s", wp_json_encode( $advance_file ) ) );

	handle_multisite( $attachment_id );

	$upload_dir = wp_upload_dir();
	$file       = rtrim( $upload_dir['basedir'], '/' ) . str_replace( '..', '', $file_path );

	$logger->info( sprintf( 'File: %s', $file ) );

	//TODO: need a better place the handle the logic
	if ( ! is_file( $file ) ) {
		if ( ! have_special_type( $private_uri ) ) {
			$s3_signed_url = apply_filters( "redirect_s3_signed_url", false, $attachment_id );
			if ( $s3_signed_url ) {
				update_hits_count( $advance_file );
				header( "Location: " . $s3_signed_url );
				exit();
			}
		} else {
			$handle = apply_filters( 'pdav3_check_user_access_private_link', $advance_file );
			if ( $handle === true ) {
				apply_filters( 'pda_v3_handle_file_return_for_private_link', $attachment_id, $advance_file->type );
			} else {
				file_not_found();
				exit();
			}
		}
		$logger->info( 'It not a file, errr!!!' );
		file_not_found();
	}

	$pda_helper = new Pda_v3_Gold_Helper();
	$mime_type  = $pda_helper->pda_mime_content_type( $file );
	if ( ! $mime_type ) {
		forbidden();
	}

	$data_private_link = array(
		'user_id'           => get_current_user_id(),
		'link_id'           => $advance_file->ID,
		'link_type'         => PDA_v3_Constants::PDA_PRIVATE_LINK,
		'mime_type'         => $mime_type,
		'file'              => $file,
		'private_link_type' => $advance_file->type,
		'private_url'       => $private_uri,
		'attachment_id'     => $attachment_id
	);

	do_action( 'insert_country', $advance_file->ID );

	$setting           = new Pda_Gold_Functions();
	$is_force_download = $setting->getSettings( PDA_v3_Constants::FORCE_DOWNLOAD ) == "true";
	if ( have_special_type( $private_uri ) ) {
		$handle = apply_filters( 'pdav3_check_user_access_private_link', $advance_file );
		if ( $handle === true ) {
			apply_filters( 'pda_v3_handle_file_return_for_private_link', $attachment_id, $advance_file->type );
			send_file_to_client( $mime_type, $file, $data_private_link, $is_force_download );
		} else {
			file_not_found();
			exit();
		}
	}

	if ( PDA_Services::get_instance()->is_block_ip_private_link( $advance_file ) ) {
		file_not_found();
		die();
	}

	$logger->info( 'Have never blocked by IP' );

	$query_str = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY );
	parse_str( $query_str, $query_params );
	if ( array_key_exists( 'size', $query_params ) ) {
		$file = $setting->handle_file_size( $file, $query_params['size'] );
	}

	if ( file_exists( $file ) ) {
		//Update hits count
		update_hits_count( $advance_file );

		do_action('pda_gold_before_sending_private_link_content', $file, $attachment_id, $advance_file );

		send_file_to_client( $mime_type, $file, $data_private_link, $is_force_download );
	} else {
		file_not_found();
	}
}

/**
 * @param $private_uri
 *
 * @return bool
 */
function have_special_type( $private_uri ) {
	$repo         = new PDA_v3_Gold_Repository();
	$advance_file = $repo->get_advance_file_by_url_and_type_is_special( $private_uri );

	return $advance_file !== null ? true : false;
}

/**
 * @param $advance_file
 *
 * @return bool
 */
function is_under_limited_downloads( $advance_file ) {
	if ( isset( $advance_file->limit_downloads ) ) {
		return $advance_file->hits_count >= $advance_file->limit_downloads;
	} else {
		return false;
	}
}

/**
 * @param $advance_file
 *
 * @return bool
 */
function is_expired( $advance_file ) {
	if ( ! isset( $advance_file->expired_date ) ) {
		return false;
	}

	return time() - $advance_file->expired_date > 0;
}

/**
 * @param $advance_file
 */
function update_hits_count( $advance_file ) {
	$repo = new PDA_v3_Gold_Repository();
	if ( isset( $advance_file ) ) {
		$new_hits_count = isset( $advance_file->hits_count ) ? $advance_file->hits_count + 1 : 1;
		$repo->update_private_link( $advance_file->ID, array( 'hits_count' => $new_hits_count ) );
	}
}

/**
 * @param $file
 * @param $mime_type
 *
 * @return bool
 */
function is_attachment_file( $file, $mime_type ) {
	return Pda_v3_Gold_Helper::is_image( $file, $mime_type ) || Pda_v3_Gold_Helper::is_pdf( $mime_type ) || Pda_v3_Gold_Helper::is_video( $mime_type ) || Pda_v3_Gold_Helper::is_html( $mime_type );
}

/**
 * @param      $mime_type
 * @param      $file
 * @param      $data_link
 * @param bool $is_force_download
 */
function send_file_to_client( $mime_type, $file, $data_link, $is_force_download = false ) {
	do_action( PDA_Hooks::PDA_HOOK_BEFORE_SENDING_FILE, $_SERVER, $data_link );
	do_action( 'pda_before_sending_file', $file, $data_link );

	// Tracking in PDA Statistics.
	if ( ! Pda_v3_Gold_Helper::is_image( $file, $mime_type ) && Pda_v3_Gold_Helper::only_track_http_method( $_SERVER['REQUEST_METHOD'] ) ) {
		do_action( PDA_v3_Constants::PDA_DO_ACTION_FOR_STATS, $data_link['user_id'], $data_link['link_id'], $data_link['link_type'], PDA_v3_Constants::PDA_CAN_VIEW );
	}

	
	$filename = wp_basename( $file );
	$logger   = new PDA_Logger();
	$logger->info( sprintf( "Send file to client: %s", $filename ) );

	if ( ( Pda_v3_Gold_Helper::is_video( $mime_type ) || Pda_v3_Gold_Helper::is_audio( $mime_type ) ) ) {
		$file_type = Pda_v3_Gold_Helper::is_video( $mime_type ) ? 'video/mp4' : 'audio/mp3';
		$logger->info( sprintf( "Is video - Streaming" ) );
//		require_once PDA_V3_BASE_DIR . 'includes/class-prevent-direct-access-videos-util.php';
		require_once PDA_V3_BASE_DIR . 'includes/class-prevent-direct-access-video-stream.php';
		$is_video_protection_activated = Yme_Plugin_Utils::is_plugin_activated( 'pda_video' ) === - 1;
		$is_expired_type               = array_key_exists( 'private_link_type', $data_link ) && PDA_v3_Constants::PDA_PRIVATE_LINK_EXPIRED === $data_link['private_link_type'];
		if ( $is_video_protection_activated && $is_expired_type ) {
			$logger->info( sprintf( "Video protection is activated: %s", wp_json_encode( $_SERVER ) ) );
			if ( isset( $_SERVER['HTTP_RANGE'] ) ) {
				$is_block_idm = apply_filters( 'pwp_block_download_managers', $_SERVER );
				if ( $is_block_idm === true ) {
					file_not_found();
					exit();
				}
				try {
					$logger->info( 'Start streaming' );
					$stream = new PDA_Video_Stream( $file );
					$stream->start( $file_type );
				} catch ( Exception $ex ) {
					$logger->error( $ex->getMessage() );
				}
//				PDA_Videos_Utils::serveFilePartial( $file, $filename, $mime_type );
			} else {
				$logger->info( sprintf( "Do not have http range" ) );
				file_not_found();
				exit();
			}
		} else if ( ! $is_force_download ) {
			try {
				$stream = new PDA_Video_Stream( $file );
				$stream->start( $file_type );
			} catch ( Exception $ex ) {
				$logger->error( $ex->getMessage() );
			}
		} 
	}
	
		if ( $is_force_download && isset( $data_link["private_url"] ) ) {
			$file_name_split = explode( ".", $filename );
			if ( count( $file_name_split ) > 1 ) {
				$file_private_name = $data_link["private_url"] . "." . $file_name_split[ count( $file_name_split ) - 1 ];
			} else {
				$file_private_name = $data_link["private_url"];
			}
			header( "Content-Type: application/octet-stream" );
			header( "Content-Disposition: attachment; filename=\"$file_private_name\"" );
		} else {
			header( "Content-Type: $mime_type" );
		}
		if ( false === strpos( $_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS' ) ) {
			header( 'Content-Length: ' . filesize( $file ) );
		}
		$last_modified = gmdate( 'D, d M Y H:i:s', filemtime( $file ) );
		$etag          = '"' . md5( $last_modified ) . '"';
		header( "Last-Modified: $last_modified GMT" );
		header( 'ETag: ' . $etag );
		if ( $is_force_download || ! is_attachment_file( $file, $mime_type ) ) {
			header( "Content-Disposition: attachment; filename=\"$filename\"" );
		} else {
			header( "Content-Disposition: inline; filename=\"$filename\"" );
		}
		header( 'X-Robots-Tag: none' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' ); // HTTP 1.1.
		header( 'Pragma: no-cache' ); // HTTP 1.0.
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 100000000 ) . ' GMT' ); // Proxies
		if ( is_support_x_send_file() ) {
			header( "X-Sendfile: $file" );
		}
		$client_etag = isset( $_SERVER['HTTP_IF_NONE_MATCH'] ) ? stripslashes( $_SERVER['HTTP_IF_NONE_MATCH'] ) : false;

		if ( ! isset( $_SERVER['HTTP_IF_MODIFIED_SINCE'] ) ) {
			$_SERVER['HTTP_IF_MODIFIED_SINCE'] = false;
		}

		$client_last_modified = trim( $_SERVER['HTTP_IF_MODIFIED_SINCE'] );
		// If string is empty, return 0. If not, attempt to parse into a timestamp
		$client_modified_timestamp = $client_last_modified ? strtotime( $client_last_modified ) : 0;

		// Make a timestamp for our most recent modification...
		$modified_timestamp = strtotime( $last_modified );

		if ( ( $client_last_modified && $client_etag )
			? ( ( $client_modified_timestamp >= $modified_timestamp ) && ( $client_etag == $etag ) )
			: ( ( $client_modified_timestamp >= $modified_timestamp ) || ( $client_etag == $etag ) )
		) {
			status_header( 304 );
			exit;
		}

		if ( ob_get_length() ) {
			ob_clean();
		}

		flush();

		if ( ! is_support_x_send_file() ) {
			readfile( $file );
		}
		exit;

}

/**
 * @param null $attachment_id
 * @param null $data_link
 */
function file_not_found( $attachment_id = null, $data_link = null ) {
	//TODO: find the way to know from embedded image tag
//	if ( !is_null( return_protected_image( $attachment_id ) ) ) {
//		$page_404 = return_protected_image( $attachment_id );
//	} else {
//		$page_404 = get_page_404();
//	}
	if ( ! empty( $data_link ) ) {
		if ( ! Pda_v3_Gold_Helper::is_image( $data_link['file'], $data_link['mime_type'] ) && Pda_v3_Gold_Helper::only_track_http_method( $_SERVER['REQUEST_METHOD'] ) ) {
			do_action( PDA_v3_Constants::PDA_DO_ACTION_FOR_STATS, $data_link['user_id'], $data_link['link_id'], $data_link['link_type'], PDA_v3_Constants::PDA_CANNOT_VIEW );
		}
	}
	$pda_function = new Pda_Gold_Functions();
	$pda_function->pda_file_not_found();

	/*
	$page_404 = get_page_404();

	if ( false !== $page_404 ) {
//		header( "Location: " . $page_404, true, 302 );
		wp_safe_redirect( $page_404, 302 );
//		exit();
	} else {
		$template_404 = get_404_template();
		if ( empty( $template_404 ) ) {
			$page_404 = '/pda_404';
			wp_safe_redirect( $page_404, 302 );
		}
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 );
		exit();
	}
	*/
}

/**
 * @param $attachment_id
 *
 * @return string|null
 */
function return_protected_image( $attachment_id ) {
	if ( is_null( $attachment_id ) ) {
		return null;
	}
	if ( wp_attachment_is_image( $attachment_id ) ) {
		return PDA_BASE_URL . 'public/assets/default_protected.png';
	}

	return null;
}

/**
 *
 */
function forbidden() {
	status_header( 403 );
	wp_die( __( '403. Forbidden.<br/>You cannot directly access files of this type in this directory on this server. Please contact the website administrator.', 'prevent-direct-access-gold' ) );
}

/**
 * @return bool
 */
function is_support_x_send_file() {
	return function_exists( 'apache_get_modules' ) && in_array( 'mod_xsendfile', apache_get_modules() );
}

/**
 * @return bool
 */
/*
function get_page_404() {
	$settings    = new Pda_Gold_Functions();
	$access_page = $settings->selected_roles( PDA_v3_Constants::PDA_GOLD_NO_ACCESS_PAGE );
	if ( $access_page ) {
		$link_page_404 = explode( ";", $access_page );
		if ( ! empty( $link_page_404[0] ) ) {
			return $link_page_404[0];
		} else {
			return false;
		}
	} else {
		return false;
	}
}
*/

/**
 * @param $advance_file
 * @param $data_private_link
 *
 * @deprecated
 */
function block_ip_private_link( $advance_file, $data_private_link ) {
	if ( Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) == - 1 ) {
		require_once ABSPATH . 'wp-content/plugins/wp-pda-ip-block/admin/class-wp-pda-ip-block-admin.php';
		$post_id    = $advance_file->post_id;
		$data       = Wp_Pda_Ip_Block_Admin::get_ip_block_by_post_id( $post_id );
		$ip_referer = $_SERVER['REMOTE_ADDR'];
		if ( isset( $data ) ) {
			$ip_blocks = explode( ';', $data->ip_block );
			foreach ( $ip_blocks as $ip_block ) {
				if ( strpos( $ip_block, '*' ) !== false ) {
					if ( valid_ip( $ip_block, $ip_referer ) ) {
						file_not_found();
						die();
					}
				} elseif ( $ip_block == $ip_referer ) {
					file_not_found();
					die();
				}
			}
		}
	}
}

/**
 * Valid ip
 * @deprecated
 *
 * @param string $ip_block   Ip pattern or ip address
 * @param string $ip_referer Ip from client
 *
 * @return bool
 */
function valid_ip( $ip_block, $ip_referer ) {
	$ips = explode( '.', $ip_block );
	if ( count( $ips ) === 4 ) {
		$every      = '(25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9])';
		$ips        = array_map( function ( $element ) use ( $every ) {
			if ( $element === '*' ) {
				return $every;
			}

			return $element;
		}, $ips );
		$ip_pattern = '/\b(?:' . $ips[0] . '\.' . $ips[1] . '\.' . $ips[2] . '\.' . $ips[3] . ')\b/';

		return preg_match( $ip_pattern, $ip_referer );
	}

	return false;
}

/**
 * @param $mime_type
 * @param $file
 * @param $data_original_link
 */
function handle_multisite_direct_access( $mime_type, $file, $data_original_link ) {
	if ( is_multisite() && ! Pda_Gold_Functions::check_unlimited_license() ) {
		if ( ! class_exists( 'PDA_Multisite_Api' ) ) {
			send_file_to_client( $mime_type, $file, $data_original_link );
			exit;
		}
	}
}

/**
 * @param $attachment_id
 */
function handle_multisite( $attachment_id ) {
	if ( is_multisite() && ! Pda_Gold_Functions::check_unlimited_license() ) {
		if ( class_exists( 'PDA_Multisite_Api' ) ) {
			if ( ! PDA_Multisite_Api::can_use_in_multisite() ) {
				file_not_found( $attachment_id );
				exit();
			}
		} else {
			file_not_found( $attachment_id );
			exit();
		}
	}
}
