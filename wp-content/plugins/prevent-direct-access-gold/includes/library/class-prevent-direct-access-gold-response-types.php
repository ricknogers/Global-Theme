<?php
/**
 * Created by PhpStorm.
 * User: linhlbh
 * Date: 8/14/19
 * Time: 11:00 AM
 */

/**
 * Handle error response
 *
 * @param string $message
 * @param int $status_code
 */
function pda_send_json_error( $message = 'Bad request', $status_code = 400 ) {
	wp_send_json_error( new WP_Error(400, $message ), $status_code );
	wp_die();
}
