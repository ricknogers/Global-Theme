<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 1/4/18
 * Time: 15:23
 */

class SettingsPage
{
    public function __construct() {
        add_option('FREE_PDA_SETTINGS', array(
                    'enable_image_hot_linking' => false,
                    'search_result_page_404' => null,
                    'enable_directory_listing' => null
                    ));
        $this->add_script_ip_lock();
    }

	function add_script_ip_lock() {
		wp_enqueue_script( 'pda_ip_lock', plugin_dir_url( __FILE__ ) . '../js/iplock.js', array( 'jquery' ) );
		wp_enqueue_script( 'pda_jquery_tagsinput_min', plugin_dir_url( __FILE__ ) . '../js/jquery.tagsinput.min.js', array(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-tooltip',
		) );

		wp_enqueue_script( 'pda_search_js', plugin_dir_url( __FILE__ ) . '../js/search.js', array( 'jquery' ) );
		wp_localize_script( 'pda_search_js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( 'pda_setting_page', plugin_dir_url( __FILE__ ) . '../js/pda-settings.js', array( 'jquery' ) );
		wp_localize_script( 'pda_setting_page', 'newsletter_data',
			array(
				'newsletter_url'   => admin_url( 'admin-ajax.php' ),
				'newsletter_nonce' => wp_create_nonce( 'pda_free_subscribe' ),
			)
		);

		wp_register_style( 'pda_css_jquery_ui', plugin_dir_url( __FILE__ ) . ( '../css/pda_lib.css' ), array() );
		wp_enqueue_style( 'pda_css_tagsinput' );
		wp_enqueue_style( 'pda_css_jquery_ui' );
	}

    function render_settings_page() {
        ?>
        <div class="wrap">
            <div id="icon-themes" class="icon32"></div>
	        <h2>
		        <?php _e( 'Prevent Direct Access - PDA Lite', 'prevent-direct-access' ); ?>
		        <span class="pda-version"><?php echo esc_html( PDAF_VERSION ); ?></span>
	        </h2>

            <?php
                $activate_tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
                $this->render_tabs( $activate_tab );
                $this->render_content( $activate_tab );
            ?>
        </div>
        <div id="pda_right_column_metaboxes">
            <?php $this->render_right_column(); ?>
        </div>
        <?php
    }

    function render_tabs($active_tab) {
        ?>
        <h2 class="nav-tab-wrapper">
            <a href="?page=wp_pda_options&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('Settings', 'prevent-direct-access'); ?></a>
<!--            <a href="?page=wp_pda_settings_options&tab=advance" class="nav-tab --><?php //echo $active_tab == 'advance' ? 'nav-tab-active' : ''; ?><!--">--><?php //_e('Advance Settings', 'pda-setting'); ?><!--</a>-->
            <a href="?page=wp_pda_options&tab=iplock" class="nav-tab <?php echo $active_tab == 'iplock' ? 'nav-tab-active' : ''; ?>"><?php _e('IP Restriction', 'prevent-direct-access'); ?></a>
            <a href="?page=wp_pda_options&tab=faq" class="nav-tab <?php echo $active_tab == 'faq' ? 'nav-tab-active' : ''; ?>"><?php _e('FAQ/Troubleshooting', 'prevent-direct-access'); ?></a>
<!--            <a href="?page=wp_pda_settings_options&tab=about" class="nav-tab --><?php //echo $active_tab == 'about' ? 'nav-tab-active' : ''; ?><!--">--><?php //_e('About', 'pda=setting'); ?><!--</a>-->
        </h2>
        <?php
    }

    function render_content($active_tab) {
        switch ($active_tab) {
            case 'general':
                $this->render_general_tab();
                break;
            case 'iplock':
                $this->render_iplock_tab();
                break;
            default:
                $this->render_faq_tab();
                break;
        }
    }

    function render_iplock_tab() {
        $pda_settings_ip = get_option('FREE_PDA_SETTINGS_IP');
        $ip_lock = $pda_settings_ip['ip_lock'];
        // $arr_ip_lock = explode(";", $ip_lock);
        // var_dump($arr_ip_lock);
        ?>
            <div class="main_container">
                <h3>Restrict access to private download links</h3>
                <form id="pda_free_ip_form" method="post">
	                <input type="hidden" value="<?php echo wp_create_nonce('pda_ajax_nonce_v3'); ?>" id="nonce_pda_v3"/>
	                <div style="margin-bottom:10px; margin-top: 10px">
                        <p>Blacklist these IP addresses: stop the following IP addresses from accessing private download links</p>
                    </div>
                    <input id="pda_free_pl_blacklist_ips" name="ip_lock" value="<?php _e($ip_lock); ?>" /><br>
                    <p class="description">Use the asterisk (*) for wildcard matching, e.g. 7.7.7.* will match IP from 7.7.7.0 to 7.7.7.255</p><br>
                    <input type="submit" value="<?php _e('Save Changes'); ?>" class="button button-primary" name="btn_ip_lock" id="pda_free_submit_btn">
                </form>
            </div>
        <?php
    }

    function check_option_existed($option, $option_key) {
        return is_array($option)
            && array_key_exists($option_key, $option);
    }


    function render_general_tab() {
        $pda_settings = get_option('FREE_PDA_SETTINGS');
        $enable_image_hot_linking = $pda_settings['enable_image_hot_linking'] === 'on' ? 'checked="checked"' : '';
        $hide_protected_files_in_media = isset($pda_settings['hide_protected_files_in_media']) ? ($pda_settings['hide_protected_files_in_media'] === 'on' ? 'checked="checked"' : '') : '';
        $disable_right_click = isset($pda_settings['disable_right_click']) ? ($pda_settings['disable_right_click'] === 'on' ? 'checked="checked"' : '') : '';
        $pda_settings_download = get_option('FREE_PDA_SETTINGS_DOWNLOAD');
        $enable_directory_listing = "";
        if($pda_settings) {
            if(array_key_exists("enable_directory_listing", $pda_settings)) {
                $enable_directory_listing = $pda_settings['enable_directory_listing'] === 'on' ? 'checked="checked"' : '';
            }
        }
        $title_page = "";
        $data_page = "";
        $enable_download = isset( $pda_settings_download['enable_download'] ) && $pda_settings_download['enable_download'] === 'on' ? 'checked="checked"' : '';
        $title = $this->get_link_page_404();
        if (isset($title) && !empty($title) && $title != null) {
            $data_page = implode(";", $title);
            $title_page = $title['title'];
        }
        ?>
            <div class="main_container">
<!--                --><?php //$this->render_notification_toast(); ?>
                <form id="pda_free_options">
                    <div>
                        <input type="hidden" value="<?php echo wp_create_nonce('pda_ajax_nonce_v3'); ?>" id="nonce_pda_v3"/>
                        <div>
                            <div class="inside">
                                <table class="pda_v3_settings_table" cellpadding="8">
                            <?php include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-nginx.php';
                            ?>
                            <tr>
                                <td colspan="2"><h3><?php echo esc_html__( 'FILE PROTECTION', 'prevent-direct-access' ) ?></h3></td>
                            </tr>
                            <?php
                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-file-access-permission.php';
                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-no-access-page.php';
//                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-replace-content-options.php';
                            ?>
                            <tr>
                                <td colspan="2">
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h3>
                                        <?php echo esc_html__( 'OTHER SECURITY OPTIONS', 'prevent-direct-access' ) ?>
                                        <span title="<?php echo esc_html__( 'Image hotlinking and directory protection options only work on Apache servers by default', 'prevent-direct-access' ) ?>" class="dashicons dashicons-warning pda-v3-gold-tooltip"></span>
                                    </h3>
                                </td>

                            </tr>
                            <?php
                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-prevent-right-click.php';
                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-ptotect-file.php';
                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-prevent-hotlinking.php';
                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-disable-directory-listing.php';
//                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-hide-wordpress-version.php';
//                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-block-access-info-file.php';
                            ?>
<!--                            <tr>-->
<!--                                <td colspan="2">-->
<!--                                    <hr>-->
<!--                                </td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td colspan="2"><h3>--><?php //echo esc_html__( 'ADVANCED OPTIONS', 'prevent-direct-access' ) ?><!--</h3></td>-->
<!--                            </tr>-->
<!--                            --><?php
//                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-enable-remote-log.php';
//                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-use-redirect-url.php';
//                            include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-remove-license-and-all-data.php';

                            ?>
                            <tr>
                                <td></td>
                                <td>
                                    <div class="save_general_btn">
                                        <input type="submit" value="<?php _e('Save Changes', 'prevent-direct-access'); ?>" class="button button-primary" id="pda_free_submit_btn" name="pda_free_general">
                                    </div>
                                </td>
                            </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        <?php
    }

    function get_title_page_404(){
        $pda_settings = get_option('FREE_PDA_SETTINGS');
        if (isset($pda_settings['search_result_page_404'])) {
            $page_404 = $pda_settings['search_result_page_404'];
            $title_page_404 = explode(";", $page_404);
            return /*"<b>Selected page: </b>".*/$title_page_404[1];
        }
    }

    function get_link_page_404(){
        $pda_settings = get_option(PDA_Lite_Constants::OPTION_NAME);
        if (isset($pda_settings['search_result_page_404'])) {
            $page_404 = $pda_settings['search_result_page_404'];
            $link_page_404 = explode(";", $page_404);
            if (count($link_page_404) === 2) {
                return array("link" => $link_page_404[0], "title" => $link_page_404[1]);
            }
        }
        return null;
    }

    function render_faq_tab() {
        ?>
        <div class="main_container pda_faq">
        <p><strong>Q: I get this error "Plugin could not be activated because it triggered a fatal error" when activating the plugin, what should I do?<br>
            </strong>Please check with your web hosting provider about its PHP version and make sure it supports PHP version 5.4 or greater. Our plugin's codes are not compatible with outdated PHP versions. As a matter of fact, WordPress also recommends your host supports:</p>
            <ul>
                <li>PHP version 7 or greater</li>
                <li>MySQL version 5.6 or greater OR MariaDB version 10.0 or greater</li>
                <li>HTTPS support</li>
                <li>Older PHP or MySQL versions may expose your site to security vulnerabilities</li>
            </ul>
        <p><strong>Q: Why can I still access my files through non-protected URLs?</strong><br>
            Please clear your browser's cache (press CTRL+F5 on PC, CMD+SHIFT+R on Mac) as files and especially images are usually cached by your browsers.</p>
        <p>Also, if you're using a caching plugin such as W3 Total Cache or WP Super Cache to speed up your WordPress website, please make sure you clear your cache as well. Your browsers and caching plugins could still be showing a cached (older) version of your files.</p>
        <p><strong>Q: Why am I getting 'page not found' error when using generated private link?</strong><br>
            It seems our rewrite rules on your .htaccess are not inserted properly. There are a few reasons for this:</p>
        <ul>
            <li>You edit and mess up your .htaccess codes</li>
            <li>Your WordPress folders are not structured as normal</li>
        </ul>
        <p>For example, in some rare cases, your domain root folder is located at, let's say, home/folder but your WordPress files are located at home/wp/folder. In such cases, you have to manually insert <a href="https://preventdirectaccess.com/docs/prevent-direct-access-lite/">our rewrite rules</a> to your .htaccess located at home/wp/folder.</p>
        <p>For more information, please visit our <a href="https://preventdirectaccess.com/faq/?utm_source=wp-lite&utm_content=setttings-link">official FAQ</a>.</p>
        </div>

        <?php

    }

    function render_notification_toast() {
        ?>
        <div class="notice updated is-dismissible pda-notice pda-install-elementor">
            <div class="pda-notice-inner">
                <div class="pda-notice-icon">
                    <img width="64" height="64" src="https://ps.w.org/prevent-direct-access/assets/icon-128x128.jpg?rev=1300338" alt="PDA Logo" />
                </div>
                <div class="pda-notice-content">
                    <h3><?php _e( 'Do you like Prevent Direct Access? You\'ll love its Gold version!'); ?></h3>
                    <p><?php _e( 'Please upgrade to ' ); ?>
                        <a target="_blank" href="<?php echo sprintf(constant( 'PDA_HOME_PAGE' ), 'user-website' , "settings-notification-link") ?>" target="_blank"><?php _e( 'Gold version' ); ?></a> to change default settings!</p>
                </div>
                <div class="pda-install-now">
                    <a class="button pda-install-button" target="_blank" href="<?php echo sprintf(constant( 'PDA_HOME_PAGE' ), 'settings', 'sidebar-cta') ?>"><i class="dashicons dashicons-download"></i><?php _e( 'Get it now!' ); ?></a>
                </div>
            </div>
        </div>
        <?php
    }

    function render_advanced_settings() {
        ?>
            <div class="main_container">
<!--                --><?php //$this->render_notification_toast(); ?>
                <h3><?php _e('General Options', 'prevent-direct-access')?></h3>
                <form method="post"  action="">
                    <div class="metabox-holder">
                        <div class="postbox">
                            <div class="inside">
                                <table cellpadding="8">
                                    <tbody>
                                    <tr>
                                        <td><?php _e('Enable remote log?', 'prevent-direct-access'); ?></td>
                                        <td>
                                            <input id='prefix_url' name='prefix_url' type='checkbox' />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Apply for logged users?', 'prevent-direct-access'); ?></td>
                                        <td>
                                            <input id='view_by_logged_user' type='checkbox' />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Prefix url', 'prevent-direct-access'); ?></td>
                                        <td>
                                            <input id='prefix_url' name='prefix_url' type='text' value="private" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><?php _e('Auto protect new uploaded files 123?', 'prevent-direct-access'); ?></td>
                                        <td>
                                            <input id='pda_auto_protect_new_files' name='prefix_url' type='checkbox' />
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <input type="submit" value="<?php _e('Save Changes', 'prevent-direct-access'); ?>" class="button button-primary" id="submit_general" name="submit_general">
                </form>
            </div>
        <?php
    }

    function render_subscribe_form() {
        ?>
        <?php
        include PDA_LITE_BASE_DIR . '/includes/views/view-prevent-direct-access-lite-subscribe-form.php';

    }

   function render_go_pro_page() {
        include('partials/go-pro.php');
   }


    function render_right_column() {
        $this->render_subscribe_form();
        ?>
            <div class="main_container">
                <h3><?php _e('Prevent Direct Access Plugins', 'prevent-direct-access'); ?></h3>
                <div>
	                <p>
		                You are new to PDA Lite? Check out <a target="_blank" rel="noreferrer noopener" href="https://preventdirectaccess.com/docs/prevent-direct-access-lite/?utm_source=user-website&utm_medium=settings-sidebar&utm_campaign=pda-lite#walkthrough">file protection basics</a> and <a target="_blank" rel="noreferrer noopener" href="https://preventdirectaccess.com/docs/?utm_source=user-website&utm_medium=settings-sidebar&utm_campaign=pda-lite">how our plugin works</a>.
	                </p>
                    <p><?php _e('Upgrade to PDA Gold NOW and enjoy many more powerful features:', 'prevent-direct-access'); ?></p>
                    <ul class="wpep_pro_upgrade_list">
                        <li><span class="dashicons dashicons-yes"></span><?php _e('Encrypt and Decrypt protected files', 'prevent-direct-access'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span><?php _e('Create Unlimited Private URLs', 'prevent-direct-access'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span><?php _e('Generate multiple Private URLs of multiple files', 'prevent-direct-access'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span><?php _e('Grant Logged-in Users Special Access', 'prevent-direct-access'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span><?php _e('Search & Replace Unprotected URLs in content', 'prevent-direct-access'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span><?php _e('Integrate with Top Membership Plugins', 'prevent-direct-access'); ?></li>
                        <li><span class="dashicons dashicons-yes"></span><?php _e('Support Multisite and NGINX servers', 'prevent-direct-access'); ?></li>
                    </ul>
                    <a href="<?php echo sprintf(constant( 'PDA_DOWNLOAD_PAGE' ), 'settings', 'sidebar-cta') ?>" target="_blank" class="button-primary"><?php _e('Download Now', 'prevent-direct-access'); ?></a>
                </div>
            </div>
        <?php
    }

    function render_like_plugin_column() {
        ?>
            <div class="main_container">
            	<h3><?php _e('Like this Plugin?', 'prevent-direct-access'); ?></h3>
<!--                <div class="metabox-holder">-->
<!--                    <div class="postbox">-->
                        <div class="inside">

                            <p><?php _e('If you like <b>Prevent Direct Access</b>, please leave us a <span class="pda-star dashicons dashicons-star-filled"></span> rating to motivate the team to work harder, add more powerful features and support you even better :) </br> A huge thanks in advance!', 'prevent-direct-access'); ?></p>
                            <p><a href="https://wordpress.org/support/plugin/prevent-direct-access/reviews/#new-post" target="_blank" class="button-primary"><?php _e("Let's do it", 'prevent-direct-access'); ?></a></p>

                            <?php
							if ( ! function_exists( 'plugins_api' ) ) {
								require_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
							}
							?>

                        </div>
            </div>
        <?php
    }
}
