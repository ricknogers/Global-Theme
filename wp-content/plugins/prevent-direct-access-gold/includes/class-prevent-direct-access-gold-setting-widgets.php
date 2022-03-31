<?php
/**
 * Created by PhpStorm.
 * User: conganh
 * Date: 17/05/18
 * Time: 4:11
 */

if (!class_exists('Prevent_Direct_Access_Gold_Setting_Widgets')) {
    class Prevent_Direct_Access_Gold_Setting_Widgets
    {
	    public function render_license_tab() {
		    $configs      = include( 'class-prevent-direct-access-gold-configs.php' );
		    $license      = get_option( PDA_v3_Constants::LICENSE_KEY );
		    $app_id       = get_site_option( PDA_v3_Constants::APP_ID, null );
		    $is_lifetime  = Pda_Gold_Functions::is_life_time( $app_id );
		    $license_type = Pda_Gold_Functions::get_license_type();
		    $service      = new PDA_Services();
		    $license_info = $service->get_license_info();
		    include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-license-tab.php';
	    }

        public function render_tooltip($key) {
            require_once 'class-prevent-direct-access-gold-function.php';
            $content = Pda_Gold_Functions::tooltip_content($key); ?>
            <span title="<?php echo $content ?>" class="dashicons dashicons-warning pda-v3-gold-tooltip"></span>
            <?php
        }

        public function get_title_page_404() {
            $search_result_page_404 = get_option(PDA_v3_Constants::OPTION_NAME);
            if ($search_result_page_404) {
                $options = unserialize($search_result_page_404);
                $pdas3_Settings = array_key_exists(PDA_v3_Constants::PDA_GOLD_NO_ACCESS_PAGE, $options) ? $options[PDA_v3_Constants::PDA_GOLD_NO_ACCESS_PAGE] : '';
                if ($pdas3_Settings != null && ! empty($pdas3_Settings)) {
                    $title = explode(";", $pdas3_Settings);
                    if (count($title) === 2) {
                        return array("link" => $title[0], "title" => $title[1]);
                    }
                }
            }
            return null;
        }

        public function render_general_tab() {
            $roles = get_editable_roles();
            $setting = new Pda_Gold_Functions;
            $file_access = $setting->pda_get_setting_type_is_array(PDA_v3_Constants::FILE_ACCESS_PERMISSION);
            $prefix_name = $setting->prefix_roles_name(PDA_v3_Constants::PDA_PREFIX_URL);
            $repository = new PDA_v3_Gold_Repository();
            $pages = $repository->get_all_post_and_page_publish();
            $is_main_site = is_main_site( get_current_blog_id() );
            $disabled_by_site = $is_main_site ? '' : 'disabled';
            $disabled_color_class = $is_main_site ? '' : 'pda-disable-color';
            ?>
            <div class="main_container">
                <form id="pda_setting_form">
	                <?php wp_nonce_field( 'pda_ajax_nonce_v3', 'nonce_pda_v3' ); ?>
                    <table class="pda_v3_settings_table" cellpadding="4">
                        <?php
                            if ( empty( $disabled_by_site ) ) {
                                $this->render_guides_for_multisite();
                            }
                            ?>
                            <tr id="pda-file-protection">
                                <td colspan="2"><h3><?php echo esc_html__( 'FILE PROTECTION', 'prevent-direct-access-gold' ) ?></h3></td>
                            </tr>
                            <?php
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-auto-protect-new-file-upload.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-encryption-info.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-file-access-permission.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-no-access-page.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-replace-content-options.php';
                            ?>
                            <tr>
                                <td colspan="2">
                                    <hr>
                                </td>
                            </tr>
                            <tr id="pda-private-download-link">
                                <td colspan="2"><h3><?php echo esc_html__( 'PRIVATE DOWNLOAD LINKS', 'prevent-direct-access-gold' ) ?></h3></td>
                            </tr>
                            <?php
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-private-url-prefix.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-auto-create-private-link.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-force-download.php';
                            ?>
                            <tr>
                                <td colspan="2">
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
	                                <h3><?php echo esc_html__( 'OTHER SECURITY OPTIONS', 'prevent-direct-access-gold' ) ?>
		                                <span title="<?php echo esc_html__( 'Only work on Apache servers', 'prevent-direct-access-gold' ) ?>" class="dashicons dashicons-warning pda-v3-gold-tooltip"></span>
	                                </h3>
                                </td>
                            </tr>
                            <?php
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-prevent-hotlinking.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-disable-directory-listing.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-hide-wordpress-version.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-block-access-info-file.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-allow-web-crawlers.php';
                            ?>
                            <tr>
                                <td colspan="2">
                                    <hr>
                                </td>
                            </tr>
                            <tr id="pda-advance-opts">
                                <td colspan="2"><h3><?php echo esc_html__( 'ADVANCED OPTIONS', 'prevent-direct-access-gold' ) ?></h3></td>
                            </tr>
                            <?php
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-grant-protection-roles.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-enable-remote-log.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-use-redirect-url.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-force-htaccess.php';
                            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-remove-license-and-all-data.php';
                            ?>
                    </table>
                    <p class="pda-submit-btn">
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"/>
                    </p>
                </form>
            </div>
        <?php
        }

        public function render_faq_tab() {
            $helper_url = network_admin_url('admin.php?page=pda-gold&tab=helper');
            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-faq-tab.php';
        }

        public function render_subscribe_form() {
            $current_user = wp_get_current_user();
            $user_meta = get_user_meta(get_current_user_id(), 'pda_gold_subscribe');
            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-subscribe-form.php';
        }

        public function render_like_plugin_column() {
            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-like-plugin-column.php';
        }

        public function render_invite_and_earn() {
            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-invite-and-earn.php';
        }

        public function render_migration_tab() {
            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-migration-tab.php';
        }

        public function render_ip_block_tab() {
            $ip_block = get_option('pda_gold_ip_block');
            include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-ip-block-tab.php';
        }

        public function render_magic_link_tab() {
            do_action('pda_magic_link_settings');
        }

        public function render_membership_tab() {
            do_action('pda_v3_membership_integration_settings');
            do_action('load_js_css_for_pda_integration_memberships');
        }

        public function render_helpers_tab() {
            PDA_Handle_Helper_Tab::render_helpers();
        }

        private function render_guides_for_multisite() {
            $setting = new Pda_Gold_Functions;
            $rules = Prevent_Direct_Access_Gold_Htaccess::get_the_rewrite_rules();
            $guides = implode("\n", $rules);
            if ( is_multisite() ) {
                include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-guides-for-multisite.php';
            }
        }

        public function render_contact_forms_tab() {
            do_action("pda_contact_forms");
        }

        public function render_quick_tour_tab() {
	        include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-quick-tour.php';
        }

        public function render_pda_woocommerce_tab() {
            do_action('pdav3_woocommerce_integration_settings');
            do_action('load_js_css_for_pda_integration_woocommerce');
        }

	    /**
	     * Render settings for tab protect all file in folder
	     */
        public function render_protect_all_file_in_folder_tab() {
	        do_action('pda_render_ui_for_protect_folder_tab');
	        do_action('pda_load_js_css_for_protect_folder_tab');
        }

	    /**
	     * Render settings for tab PDA 4rum integration
	     */
        public function render_pda_4rum_tab() {
	        do_action('pda_render_ui_for_4rum_integration_tab');
        }

		/**
		 * Render UI for notices.
		 */
		public function render_notices() {
			$gold_helpers = new Pda_v3_Gold_Helper();
			if ( $gold_helpers->is_show_notice_for_ppwp_plugin() ) {
				include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-notices-form.php';
			}
		}

    }
}
