<?php
	/**
	 * Created by PhpStorm.
	 * User: gaupoit
	 * Date: 6/25/18
	 * Time: 09:53
	 */

	if ( ! class_exists( 'PDA_Status' ) ) {
		/**
		 * Prevent direct access plugin status helpers class
		 * Class PDA_Status
		 */
		class PDA_Status {
			/**
			 * Render status UI
			 */
			public function render_ui() {
				$activate_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : "system_status"; // Input var okay.
				?>
                <div class="wrap">
                    <div id="icon-themes" class="icon32"></div>
                    <h2>Prevent Direct Access Gold <?php esc_html_e( 'Status', 'prevent-direct-access-gold' ); ?></h2>
					<?php
						$this->render_tabs( $activate_tab );
						$this->render_content( $activate_tab );
					?>
                </div>
				<?php
			}

			/**
			 * Render activating tab
			 *
			 * @param string $active_tab activating tab.
			 */
			public function render_tabs( $active_tab ) {
				$prefix = PDA_v3_Constants::STATUS_PAGE_PREFIX;
				?>
                <h2 class="nav-tab-wrapper">
                    <a href="?page=<?php echo esc_attr( $prefix ); ?>&tab=system_status"
                       class="nav-tab <?php echo 'system_status' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'System status', 'prevent-direct-access-gold' ); ?></a>
                    <a href="?page=<?php echo esc_attr( $prefix ); ?>&tab=tools"
                       class="nav-tab <?php echo 'tools' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Tools', 'prevent-direct-access-gold' ); ?></a>
                    <a href="?page=<?php echo esc_attr( $prefix ); ?>&tab=logs"
                       class="nav-tab <?php echo 'logs' === $active_tab ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Logs', 'prevent-direct-access-gold' ); ?></a>
                </h2>
				<?php
			}

			/**
			 * Render UI for activating tab
			 *
			 * @param string $active_tab activating tab.
			 */
			public function render_content( $active_tab ) {
				switch ( $active_tab ) {
					case 'system_status':
						$this->render_system_status();
						break;
					case 'tools':
						$this->render_tools();
						break;
                    case 'logs':
                        $this->render_logs();
				}
			}

			/**
			 * Render system status UI
			 */
			public function render_system_status() {
				$database    = $this->get_database_info();
				$environment = $this->get_environment_info();
				$security    = $this->get_security_info();
				$theme       = $this->get_theme_info();
				$tooltip     = new Prevent_Direct_Access_Gold_Setting_Widgets();
				?>
                <table class="pda_stattus_table pda_wrapper_table" cellspacing="0">
                    <thead>
                    <tr class="title_talbe">
                        <th><h2><?php esc_html_e( 'WordPress Environment', 'prevent-direct-access-gold' ); ?></h2></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php esc_html_e( 'Home URL', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'home_url' ) ?></td>
                        <td><?php echo esc_html( $environment['home_url'] ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Site URL', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'site_url' ) ?></td>
                        <td><?php echo esc_html( $environment['site_url'] ); ?></td>
                    </tr>
                    <tr>
                        <td>Prevent Direct Access <?php esc_html_e( 'Version', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'version' ) ?></td>
                        <td><?php echo esc_html( $environment['version'] ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Wordpress Version', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'wp_version' ) ?></td>
                        <td><?php echo esc_html( $environment['wp_version'] ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'WordPress Multisite', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'wp_multisite' ) ?></td>
                        <td><?php echo ( $environment['wp_multisite'] ) ? '<span class="dashicons-yes-color dashicons dashicons-yes"></span>' : '&ndash;'; ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'WordPress Memory Limit', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'wp_memory_limit' ) ?></td>
                        <td>
							<?php
								if ( $environment['wp_memory_limit'] < 67108864 ) {
									echo '<a href="https://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">Increasing memory allocated to PHP</a> ';
								} else {
									echo esc_html( size_format( $environment['wp_memory_limit'] ) );
								}
							?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'WordPress Debug Mode', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'wp_debug_mode' ) ?></td>
                        <td>
							<?php if ( $environment['wp_debug_mode'] ) : ?>
                                <span class="dashicons-yes-color dashicons dashicons-yes"></span>
							<?php else : ?>
                                &ndash;
							<?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'WordPress Cron', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'wp_cron' ) ?></td>
                        <td>
							<?php if ( $environment['wp_cron'] ) : ?>
                                <span class="dashicons-yes-color dashicons dashicons-yes"></span>
							<?php else : ?>
                                &ndash;
							<?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Language', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'language' ) ?></td>
                        <td><?php echo esc_html( $environment['language'] ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'External object cache', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'external_object_cache' ) ?></td>
                        <td>
							<?php if ( $environment['external_object_cache'] ) : ?>
                                <span class="dashicons-yes-color dashicons dashicons-yes"></span>
							<?php else : ?>
                                &ndash;
							<?php endif; ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <table class="pda_stattus_table pda_wrapper_table" cellspacing="0">
                    <thead>
                    <tr class="title_talbe">
                        <th><h2><?php esc_html_e( 'Server Environment', 'prevent-direct-access-gold' ); ?></h2></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php esc_html_e( 'Server info', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'server_info' ) ?></td>
                        <td><?php echo esc_html( $environment['server_info'] ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'PHP Version', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'php_version' ) ?></td>
                        <td><?php echo esc_html( $environment['php_version'] ); ?></td>
                    </tr>
					<?php if ( function_exists( 'ini_get' ) ) { ?>
                        <tr>
                            <td><?php esc_html_e( 'PHP Post Max Size', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'php_post_max_size' ) ?></td>
                            <td><?php echo esc_html( size_format( $environment['php_post_max_size'] ) ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'PHP Time Limit', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'php_max_execution_time' ) ?></td>
                            <td><?php echo esc_html( $environment['php_max_execution_time'] ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'PHP Max Input Vars', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'php_max_input_vars' ) ?></td>
                            <td><?php echo esc_html( $environment['php_max_input_vars'] ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'cURL Version', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'curl_version' ) ?></td>
                            <td><?php echo esc_html( $environment['curl_version'] ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'SUHOSIN Installed', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'suhosin_installed' ) ?></td>
                            <td><?php echo $environment['suhosin_installed'] ? '<span class="dashicons-yes-color dashicons dashicons-yes"></span>' : '&ndash;'; ?></td>
                        </tr>
					<?php } ?>
					<?php if ( $environment['mysql_version'] ) { ?>
                        <tr>
                            <td><?php esc_html_e( 'MySQL Version', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'mysql_version' ) ?></td>
                            <td><?php echo esc_html( $environment['mysql_version'] ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Max Upload Size', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'max_upload_size' ) ?></td>
                            <td><?php echo esc_html( size_format( $environment['max_upload_size'] ) ); ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Default Timezone is UTC', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'default_timezone' ) ?></td>
                            <td>
								<?php
									if ( 'UTC' !== $environment['default_timezone'] ) {
										echo sprintf( esc_html__( 'Default timezone is %s - it should be UTC', 'prevent-direct-access-gold' ), esc_html( $environment['default_timezone'] ) );
									} else {
										echo '<span class="dashicons dashicons-yes dashicons-yes-color"></span>';
									}
								?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'fsockopen/cURL', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'fsockopen_or_curl_enabled' ) ?></td>
                            <td>
								<?php
									if ( $environment['fsockopen_or_curl_enabled'] ) {
										echo '<span class="dashicons-yes-color dashicons dashicons-yes"></span>';
									} else {
										echo esc_html__( 'Your server does not have fsockopen or cURL enabled - PayPal IPN and other scripts which communicate with other servers will not work. Contact your hosting provider.', 'prevent-direct-access-gold' );
									}
								?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'DOMDocument', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'domdocument_enabled' ) ?></td>
                            <td>
								<?php
									if ( $environment['domdocument_enabled'] ) {
										echo '<span class="dashicons-yes-color dashicons dashicons-yes"></span>';
									} else {
										echo sprintf( esc_html__( 'Your server does not have the %s class enabled - HTML/Multipart emails, and also some extensions, will not work without DOMDocument.', 'prevent-direct-access-gold' ), '<a href="https://php.net/manual/en/class.domdocument.php">DOMDocument</a>' );
									}
								?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'GZip', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'gzip_enabled' ) ?></td>
                            <td>
								<?php
									if ( $environment['gzip_enabled'] ) {
										echo '<span class="dashicons-yes-color dashicons dashicons-yes"></span>';
									} else {
										echo sprintf( esc_html__( 'Your server does not support the %s function - this is required to use the GeoIP database from MaxMind.', 'prevent-direct-access-gold' ), '<a href="https://php.net/manual/en/zlib.installation.php">gzopen</a>' );
									}
								?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Multibyte String', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'mbstring_enabled' ) ?></td>
                            <td>
								<?php
									if ( $environment['mbstring_enabled'] ) {
										echo '<span class="dashicons-yes-color dashicons dashicons-yes"></span>';
									} else {
										echo sprintf( esc_html__( 'Your server does not support the %s functions - this is required for better character encoding. Some fallbacks will be used instead for it.', 'prevent-direct-access-gold' ), '<a href="https://php.net/manual/en/mbstring.installation.php">mbstring</a>' );
									}
								?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Remote Post', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'remote_post_successful' ) ?></td>
                            <td>
								<?php
									if ( $environment['remote_post_successful'] ) {
										echo '<span class="dashicons-yes-color dashicons dashicons-yes"></span>';
									} else {
										echo sprintf( esc_html__( '%s failed. Contact your hosting provider.', 'prevent-direct-access-gold' ), 'wp_remote_post()' ) . ' ' . esc_html( $environment['remote_post_response'] );
									}
								?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'Remote Get', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'remote_get_successful' ) ?></td>
                            <td>
								<?php
									if ( $environment['remote_get_successful'] ) {
										echo '<span class="dashicons-yes-color dashicons dashicons-yes"></span>';
									} else {
										echo sprintf( esc_html__( '%s failed. Contact your hosting provider.', 'prevent-direct-access-gold' ), 'wp_remote_get()' ) . ' ' . esc_html( $environment['remote_get_response'] );
									}
								?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e( 'PDA Log Directory Writable', 'prevent-direct-access-gold' ); ?>:</td>
                            <td><?php $tooltip->render_tooltip( 'pda_log_writable' ) ?></td>
                            <td>
								<?php
									if ( $environment['pda_log_writable'] ) {
										echo '<span class="dashicons-yes-color dashicons dashicons-yes"></span>';
									} else {
										echo sprintf( esc_html__( 'Please change the write permission to directory %s', 'prevent-direct-access-gold' ),  PDA_LOG_DIR ) . ' ' . esc_html( $environment['pda_log_writable'] );
									}
								?>
                            </td>
                        </tr>
					<?php } ?>
                    </tbody>
                </table>

                <table class="pda_stattus_table pda_wrapper_table" cellspacing="0">
                    <thead>
                    <tr class="title_talbe">
                        <th><h2><?php esc_html_e( 'Database', 'prevent-direct-access-gold' ); ?></h2></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Prevent Direct Access <?php esc_html_e( 'Database Version', 'prevent-direct-access-gold' ); ?>
                            :
                        </td>
                        <td><?php $tooltip->render_tooltip( 'pda_database_version' ) ?></td>
                        <td><?php echo esc_html( $database['pda_database_version'] ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Database prefix', 'prevent-direct-access-gold' ); ?>:</td>
                        <td></td>
                        <td><?php echo esc_html( $database['database_prefix'] ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Total Database Size', 'prevent-direct-access-gold' ); ?>:</td>
                        <td></td>
                        <td><?php echo esc_html( $database['total_size'] ) . "MB"; ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Database Data Size', 'prevent-direct-access-gold' ); ?>:</td>
                        <td></td>
                        <td><?php echo esc_html( $database['data_size'] ) . "MB"; ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Database Index Size', 'prevent-direct-access-gold' ); ?>:</td>
                        <td></td>
                        <td><?php echo esc_html( $database['index_size'] ) . "MB"; ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( $database['wp_prevent_direct_access']->name, 'prevent-direct-access-gold' ); ?>
                            :
                        </td>
                        <td></td>
                        <td><?php echo "Data: " . esc_html( $database['wp_prevent_direct_access']->data ) . "MB + Index: " . esc_html( $database['wp_prevent_direct_access']->index ) . "MB"; ?></td>
                    </tr>
                    </tbody>
                </table>

                <table class="pda_stattus_table pda_wrapper_table" cellspacing="0">
                    <thead>
                    <tr class="title_talbe">
                        <th><h2><?php esc_html_e( 'Security', 'prevent-direct-access-gold' ); ?></h2></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php esc_html_e( 'Secure connection (HTTPS)', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'secure_connection' ) ?></td>
                        <td>
							<?php if ( $security['secure_connection'] ) : ?>
                                <span class="dashicons dashicons-yes dashicons-yes-color"></span>
							<?php else :
								echo "<span class='dashicons-warning-error'>" . wp_kses_post( sprintf( __( 'Your site is not using HTTPS. <a href="%s" target="_blank">Learn more about HTTPS and SSL Certificates</a>.', 'prevent-direct-access-gold' ), 'https://docs.pda.com/document/ssl-and-https/' ) ) . "</span>";
							endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Hide errors from visitors', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'hide_errors' ) ?></td>
                        <td>
							<?php if ( $security['hide_errors'] ) : ?>
                                <span class="dashicons dashicons-yes dashicons-yes-color"></span>
							<?php else : ?>
                                <span class='dashicons-warning-error'><?php esc_html_e( 'Error messages should not be shown to visitors.', 'prevent-direct-access-gold' ); ?></span>
							<?php endif; ?>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <table class="pda_stattus_table pda_wrapper_table" cellspacing="0">
                    <thead>
                    <tr class="title_talbe">
                        <th><h2><?php esc_html_e( 'Theme', 'prevent-direct-access-gold' ); ?></h2></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php esc_html_e( 'Name', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'name_theme' ) ?></td>
                        <td><?php echo esc_html( $theme['name'] ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Version', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'version_theme' ) ?></td>
                        <td><?php echo esc_html( $theme['version'] ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Author URL', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'author_url_theme' ) ?></td>
                        <td><?php echo esc_html( $theme['author_url'] ); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e( 'Child theme', 'prevent-direct-access-gold' ); ?>:</td>
                        <td><?php $tooltip->render_tooltip( 'is_child_theme_theme' ) ?></td>
                        <td>
							<?php
								if ( $theme['is_child_theme'] ) {
									echo '<span class="dashicons dashicons-yes dashicons-yes-color"></span>';
								} else {
									echo wp_kses_post( sprintf( __( 'If you are modifying Prevent Direct Access Gold on a parent theme that you did not build personally we recommend using a child theme. See: <a href="%s" target="_blank">How to create a child theme</a>', 'prevent-direct-access-gold' ), 'https://codex.wordpress.org/Child_Themes' ) );
								}
							?>
                        </td>
                    </tr>
                    </tbody>
                </table>
				<?php
			}

			/**
			 * Render tools UI
			 */
			public function render_tools() {
				if ( isset( $_POST['save-change-htaccess'] ) ) {
					$new_content_htaccess = $_POST['htaccess_content'];
					if ( $this->PDA_WriteNewHtaccess( $new_content_htaccess ) ) {
						?>
                        <div class="updated"><p><strong>File has been successfully changed!</strong></p></div><?php
					} else {
						?>
                        <div class="error"><p><strong>The file could not be saved!</strong></p></div><?php
					}
				}

				$htaccess = pda_get_htaccess_rule_path();
				if ( ! file_exists( $htaccess ) ) {
					?>
                    <div class="error"><p><strong>Htaccess file does not exists!</strong></p></div><?php
				} else {
					if ( ! is_readable( $htaccess ) ) {
						?>
                        <div class="error"><p><strong>Htaccess file cannot read!</strong></p></div><?php
					} else {
						$content_htaccess = file_get_contents( $htaccess, false, null );
						if ( $content_htaccess === false ) {
							?>
                            <div class="error"><p><strong>Htaccess file cannot read!</strong></p></div><?php
						} else {
							?>
                            <div class="pda-wrap-htaccess">
                                <form method="post" action="admin.php?page=pda-status&tab=tools">
                                    <h3>Edit your .htaccess file</h3>
                                    <textarea class="pda-wrap-text-htaccess" name="htaccess_content"
                                              id=""><?php echo $content_htaccess; ?></textarea>
                                    <input type="submit" name="save-change-htaccess"
                                           class="button button-primary save-htaccess"
                                           value="Save changes <?php _e( '&raquo;' ); ?>">
                                </form>
                            </div>
							<?php
						}
					}
				}
			}

			public function PDA_WriteNewHtaccess( $new_content_htaccess ) {
				$old_content_htaccess = pda_get_htaccess_rule_path();
				clearstatcache();
				if ( file_exists( $old_content_htaccess ) ) {
					if ( is_writable( $old_content_htaccess ) ) {
						unlink( $old_content_htaccess );
					} else {
						chmod( $old_content_htaccess, 0666 );
						unlink( $old_content_htaccess );
					}
				}

				$new_content_htaccess = trim( $new_content_htaccess );
				$new_content_htaccess = str_replace( '\\\\', '\\', $new_content_htaccess );
				$new_content_htaccess = str_replace( '\"', '"', $new_content_htaccess );
				$PDA_write_success    = file_put_contents( $old_content_htaccess, $new_content_htaccess, LOCK_EX );
				clearstatcache();
				if ( ! file_exists( $old_content_htaccess ) && $PDA_write_success === false ) {
					unset( $old_content_htaccess );
					unset( $new_content_htaccess );
					unset( $PDA_write_success );

					return false;
				} else {
					unset( $old_content_htaccess );
					unset( $new_content_htaccess );
					unset( $PDA_write_success );

					return true;
				}
			}

			public function get_theme_info() {
				$active_theme = wp_get_theme();

				$active_theme_info = array(
					'name'           => $active_theme->name,
					'version'        => $active_theme->version,
					'author_url'     => esc_url_raw( $active_theme->{'Author URI'} ),
					'is_child_theme' => is_child_theme(),
				);

				return $active_theme_info;
			}

			public function get_database_info() {
				global $wpdb;
				$database_table_sizes     = $wpdb->get_results(
					$wpdb->prepare(
						"SELECT
				    table_name AS 'name',
				    round( ( data_length / 1024 / 1024 ), 2 ) 'data',
				    round( ( index_length / 1024 / 1024 ), 2 ) 'index'
				FROM information_schema.TABLES
				WHERE table_schema = %s
				ORDER BY name ASC;",
						DB_NAME
					)
				);
				$data_size                = 0;
				$index_size               = 0;
				$wp_prevent_direct_access = "";
				foreach ( $database_table_sizes as $table ) {
					if ( $table->name = 'wp_prevent_direct_access' ) {
						$wp_prevent_direct_access = $table;
					}
					$data_size  += $table->data;
					$index_size += $table->index;
				}
				$total_size = $data_size + $index_size;

				return array(
					'pda_database_version'     => get_option( PDA_v3_Constants::$db_version ),
					'database_prefix'          => $wpdb->prefix,
					'total_size'               => $total_size,
					'data_size'                => $data_size,
					'index_size'               => $index_size,
					'wp_prevent_direct_access' => $wp_prevent_direct_access,
				);
			}

			function pda_let_to_num( $size ) {
				$l    = substr( $size, - 1 );
				$ret  = substr( $size, 0, - 1 );
				$byte = 1024;

				switch ( strtoupper( $l ) ) {
					case 'P':
						$ret *= 1024;
					// No break.
					case 'T':
						$ret *= 1024;
					// No break.
					case 'G':
						$ret *= 1024;
					// No break.
					case 'M':
						$ret *= 1024;
					// No break.
					case 'K':
						$ret *= 1024;
					// No break.
				}

				return $ret;
			}

			function pda_get_server_database_version() {
				global $wpdb;

				if ( empty( $wpdb->is_mysql ) ) {
					return array(
						'string' => '',
						'number' => '',
					);
				}

				if ( $wpdb->use_mysqli ) {
					$server_info = mysqli_get_server_info( $wpdb->dbh ); // @codingStandardsIgnoreLine.
				} else {
					$server_info = mysql_get_server_info( $wpdb->dbh ); // @codingStandardsIgnoreLine.
				}

				return array(
					'string' => $server_info,
					'number' => preg_replace( '/([^\d.]+).*/', '', $server_info ),
				);
			}

			function pda_clean( $var ) {
				if ( is_array( $var ) ) {
					return array_map( 'wc_clean', $var );
				} else {
					return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
				}
			}

			public function get_security_info() {
				return array(
					'secure_connection' => 'https' === substr( get_home_url(), 0, 5 ),
					'hide_errors'       => ! ( defined( 'WP_DEBUG' ) && defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG && WP_DEBUG_DISPLAY ) || 0 === intval( ini_get( 'display_errors' ) ),
				);
			}

			public function get_environment_info() {
				$curl_version = '';
				if ( function_exists( 'curl_version' ) ) {
					$curl_version = curl_version();
					$curl_version = $curl_version['version'] . ', ' . $curl_version['ssl_version'];
				}
				$database_version = $this->pda_get_server_database_version();
				// Test POST requests.
				$post_response            = wp_safe_remote_post(
					home_url(),
					array(
						'timeout'     => 10,
						'httpversion' => '1.1',
						'body'        => array(
							'cmd' => '_notify-validate',
						),
					)
				);
				$post_response_successful = false;
				if ( ! is_wp_error( $post_response ) && $post_response['response']['code'] >= 200 && $post_response['response']['code'] < 300 ) {
					$post_response_successful = true;
				}
				// Test GET requests.
				$get_response            = wp_safe_remote_get( home_url() );
				$get_response_successful = false;
				if ( ! is_wp_error( $post_response ) && $post_response['response']['code'] >= 200 && $post_response['response']['code'] < 300 ) {
					$get_response_successful = true;
				}
				$wp_memory_limit = $this->pda_let_to_num( WP_MEMORY_LIMIT );
				if ( function_exists( 'memory_get_usage' ) ) {
					$wp_memory_limit = max( $wp_memory_limit, $this->pda_let_to_num( @ini_get( 'memory_limit' ) ) );
				}

				return array(
					'home_url'                  => home_url(),
					'site_url'                  => site_url(),
					'version'                   => PDA_GOLD_V3_VERSION,
					'wp_version'                => get_bloginfo( 'version' ),
					'wp_multisite'              => is_multisite(),
					'wp_memory_limit'           => $wp_memory_limit,
					'wp_debug_mode'             => ( defined( 'WP_DEBUG' ) && WP_DEBUG ),
					'wp_cron'                   => ! ( defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON ),
					'language'                  => get_locale(),
					'external_object_cache'     => wp_using_ext_object_cache(),
					'server_info'               => isset( $_SERVER['SERVER_SOFTWARE'] ) ? $this->pda_clean( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '',
					'php_version'               => phpversion(),
					'php_post_max_size'         => $this->pda_let_to_num( ini_get( 'post_max_size' ) ),
					'php_max_execution_time'    => ini_get( 'max_execution_time' ),
					'php_max_input_vars'        => ini_get( 'max_input_vars' ),
					'curl_version'              => $curl_version,
					'suhosin_installed'         => extension_loaded( 'suhosin' ),
					'max_upload_size'           => wp_max_upload_size(),
					'mysql_version'             => $database_version['number'],
					'mysql_version_string'      => $database_version['string'],
					'default_timezone'          => date_default_timezone_get(),
					'fsockopen_or_curl_enabled' => ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ),
					'domdocument_enabled'       => class_exists( 'DOMDocument' ),
					'gzip_enabled'              => is_callable( 'gzopen' ),
					'mbstring_enabled'          => extension_loaded( 'mbstring' ),
					'remote_post_successful'    => $post_response_successful,
					'remote_post_response'      => ( is_wp_error( $post_response ) ? $post_response->get_error_message() : $post_response['response']['code'] ),
					'remote_get_successful'     => $get_response_successful,
					'remote_get_response'       => ( is_wp_error( $get_response ) ? $get_response->get_error_message() : $get_response['response']['code'] ),
                    'pda_log_writable'          => @is_writable(  trailingslashit( PDA_LOG_DIR ) . 'index.html' ),
				);
			}

			public function render_logs() {
			    require_once PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-status-log-content.php';
			    PDA_Status_Log_View::render();
            }
		}
	}


