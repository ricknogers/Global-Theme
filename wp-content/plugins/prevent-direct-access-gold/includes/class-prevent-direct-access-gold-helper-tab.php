<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 4/12/18
 * Time: 14:34
 */

if ( ! class_exists( 'PDA_Handle_Helper_Tab' ) ) {
	class PDA_Handle_Helper_Tab {

		/**
		 * Render helper tab
		 */
		public static function render_helpers() {
			$server_name = Pda_v3_Gold_Helper::get_instance()->get_server_name();
			$data        = self::render_opening_guide_data( $server_name );
			$btn_name    = $data['btn_name'];
			// Using them for view.
			$open_message = $data['open_message'];
			$end_message  = $data['end_message'];
			global $is_apache;
			$home_path = get_home_path();
			?>
			<div class="main_container pda_v3_settings_table pda_v3_wrap_helper_tab">
				<?php if ( Pda_Gold_Functions::is_fully_activated() ) {
					self::render_status( $is_apache );
					self::render_rewrite_rules( $home_path, $server_name, $btn_name );
				} else {
					require_once PDA_V3_BASE_DIR . '/includes/views/helpers-tab/view-prevent-direct-access-gold-rules-form.php';
				} ?>
			</div>
			<?php
		}

		/**
		 * Render status
		 *
		 * @param $is_apache
		 */
		public static function render_status( $is_apache ) {
			$helpers             = new Pda_Gold_Functions();
			$use_redirect_url    = $helpers->get_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS );
			$rewrite_rule_status = Prevent_Direct_Access_Gold_Htaccess::check_rewrite_rules_by_private_link();
			$status_message      = self::get_status_message( $is_apache, $use_redirect_url, $rewrite_rule_status );
			?>
			<h3>Plugin Status</h3>
			<div id="pda-plugin-status">
				<?php _e( $status_message, 'prevent-direct-access-gold' ); ?>
			</div>
			<hr>
			<?php
		}

		/**
		 * Get status message
		 *
		 * @param $is_apache
		 * @param $use_redirect_url
		 * @param $rewrite_rule_status
		 *
		 * @return string
		 */
		public static function get_status_message( $is_apache, $use_redirect_url, $rewrite_rule_status ) {
			$rule_checker = new PDA_v3_Rewrite_Rule_Checker();
			if ( $use_redirect_url ) {
				if ( $is_apache ) {
					$isset_limitation = $rule_checker->allow_access_pda_folder();
				} else {
					$isset_limitation = - 1 === $rewrite_rule_status || false === $rewrite_rule_status;
				}

				if ( $isset_limitation ) {
					return PDA_v3_Constants::PLUGIN_RUN_WITH_LIMITATION;
				}

				return PDA_v3_Constants::PLUGIN_RUN_OK;
			}

			if ( $is_apache ) {
				if ( true !== $rewrite_rule_status ) {
					return PDA_v3_Constants::PLUGIN_CANNOT_RUN;
				}

				if ( $rule_checker->allow_access_pda_folder() ) {
					return PDA_v3_Constants::PLUGIN_RUN_OK;
				}

				return PDA_v3_Constants::PLUGIN_RULE_ERROR_APACHE;
			}

			if ( true === $rewrite_rule_status ) {
				return PDA_v3_Constants::PLUGIN_RUN_OK;
			}

			return PDA_v3_Constants::PLUGIN_CANNOT_RUN;
		}

		/**
		 * Render Rewrite Rules
		 *
		 * @param $home_path
		 * @param $server_name
		 * @param $btn_name
		 */
		public static function render_rewrite_rules( $home_path, $server_name, $btn_name ) {
			?>
			<h3>Rewrite Rules</h3>
			<?php
			self::render_guides_after_fully_activated( $server_name, $home_path );
			?>
			<form method="post" id="enable_pda_v3_form">
				<?php wp_nonce_field( 'pda_ajax_nonce_v3', 'nonce_pda_v3' ); ?>
				<?php submit_button( __( $btn_name, 'prevent-direct-access-gold' ), 'primary', 'enable_pda_v3', false ); ?>
			</form>
			<?php
		}

		/**
		 * @param $server_type it should be Apache, NGINX, IIS or custom server type.
		 *
		 * @return array
		 */
		public static function render_opening_guide_data( $server_type ) {
			$default_btn_name = 'Check rewrite rules';
			switch ( $server_type ) {
				case 'apache':
					return array(
						'btn_name'     => $default_btn_name,
						'open_message' => 'If your .htaccess file were writable, Prevent Direct Access Gold could do this automatically for you, but it isn’t. ',
						'end_message'  => 'Please follow these simple steps below to get our plugin up and running:'
					);
				case 'nginx':
					return array(
						'btn_name'     => $default_btn_name,
						'open_message' => 'It looks like you’re using NGINX webserver. Since NGINX does not have .htaccess-type capability, ',
						'end_message'  => 'Prevent Direct Access Gold cannot update your server configuration automatically for you. Here’s how you can do it manually:'
					);
				case 'iis':
					return array(
						'btn_name'     => $default_btn_name,
						'open_message' => 'It looks like you\'re using IIS webserver. Since IIS doesn’t have .htaccess-type capability, ',
						'end_message'  => 'Prevent Direct Access Gold cannot modify your server configuration automatically for you. Here\'s how you can do it manually:',
					);
				default:
					return array(
						'btn_name'     => $default_btn_name,
						'open_message' => 'We’re unable to determine the actual server type that your site is hosted on.',
						'end_message'  => 'Prevent Direct Access Gold cannot update your server configuration automatically for you. Here’s how you can do it manually:'
					);
			}
		}

		/**
		 * Get Rules by server name
		 *
		 * @param $server_name
		 *
		 * @return array|string
		 */
		public static function get_rules( $server_name ) {
			switch ( $server_name ) {
				case PDA_v3_Constants::APACHE_SERVER:
					return Prevent_Direct_Access_Gold_Htaccess::get_the_rewrite_rules();
				case PDA_v3_Constants::NGINX_SERVER:
					return Prevent_Direct_Access_Gold_Htaccess::get_nginx_rules();
				case PDA_v3_Constants::IIS_SERVER:
					return Prevent_Direct_Access_Gold_Htaccess::get_iis_rules();
				default:
					return '';
			}
		}

		/**
		 * Render guide
		 *
		 * @param $server_name
		 * @param $home_path
		 */
		public static function render_guides_after_fully_activated( $server_name, $home_path ) {
			switch ( $server_name ) {
				case PDA_v3_Constants::APACHE_SERVER:
					require_once PDA_V3_BASE_DIR . '/includes/views/helpers-tab/view-prevent-direct-access-gold-apache.php';
					break;
				case PDA_v3_Constants::NGINX_SERVER:
					require_once PDA_V3_BASE_DIR . '/includes/views/helpers-tab/view-prevent-direct-access-gold-nginx.php';
					break;
				case PDA_v3_Constants::IIS_SERVER:
					require_once PDA_V3_BASE_DIR . '/includes/views/helpers-tab/view-prevent-direct-access-gold-iis.php';
					break;
				default:
					require_once PDA_V3_BASE_DIR . '/includes/views/helpers-tab/view-prevent-direct-access-gold-others.php';
					break;
			}
		}
	}
}
