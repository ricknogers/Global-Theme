<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://preventdirectaccess.com/extensions/?utm_source=user-website&utm_medium=pluginsite_link&utm_campaign=pda_gold
 * @since      1.0.0
 *
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Prevent_Direct_Access_Gold
 * @subpackage Prevent_Direct_Access_Gold/public
 * @author     BWPS <hello@preventdirectaccess.com>
 */
class Prevent_Direct_Access_Gold_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Prevent_Direct_Access_Gold_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Prevent_Direct_Access_Gold_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Prevent_Direct_Access_Gold_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Prevent_Direct_Access_Gold_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	}

	/**
	 * Handle protected file
	 * @used-by PDA_S3
	 */
	public function pda_s3_handle_protected_file() {
		if( isset( $_GET[PDA_v3_Constants::$secret_param]) && !empty( $_GET[PDA_v3_Constants::$secret_param]) ) {

			if( isset( $_GET[PDA_v3_Constants::$secret_param_test]) && $_GET[PDA_v3_Constants::$secret_param_test] ) {
				die('pass');
			}

			require_once( PDA_V3_BASE_DIR . '/includes/class-prevent-direct-access-handle-file-request.php' );

			if( array_key_exists(PDA_v3_Constants::$secret_private_link_name, $_GET) && !empty( $_GET[PDA_v3_Constants::$secret_private_link_name] ) ) {
				//handle private link
				$private_uri = $_GET[PDA_v3_Constants::$secret_param];
				pda_v3_handle_private_request( $private_uri );
				exit();
			} else {
				$file = $_GET[PDA_v3_Constants::$secret_param];
				pda_v3_handle_protected_file_request($file);
				exit();
			}
		}
	}

	/**
	 * Handle download log file
	 */
	public function pda_s3_handle_download_log_file() {
		if( isset ( $_GET['pda_log' ] ) ) {
			if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( wp_unslash( $_REQUEST['_wpnonce'] ), 'download_log' ) ) { // WPCS: input var ok, sanitization ok.
				wp_die( esc_html__( 'Action failed. Please refresh the page and retry.', 'prevent-direct-access-gold' ) );
			}
			$file_url = admin_url( 'admin.php?page=pda-status&tab=logs' );
			if ( ! empty( $_REQUEST['handle'] ) ) {  // WPCS: input var ok.
				$log_handler = new PDA_Log_Handler_file();
				$log_handler->download( wp_unslash( $_REQUEST['handle'] ) ); // WPCS: input var ok, sanitization ok.
				exit();
			} else {
				wp_safe_redirect( esc_url_raw( $file_url ) );
				exit();
			}
		}
	}

	/**
	 * @param $attrs
	 *
	 * @return string
	 * @throws Exception
	 */
	public function generate_pda_private_link_shortcode( $attrs ) {
		try {
			return PDA_Services::get_instance()->generate_pda_private_link_shortcode( $attrs );
		} catch ( Exception $exception ) {
			return '<font color="red">' . __( $exception->getMessage(), 'prevent-direct-access-gold') . '</font>';
		}
	}
}
