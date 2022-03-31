<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 4/2/18
 * Time: 09:57
 */

if ( ! class_exists( 'Prevent_Direct_Access_Gold_Settings' ) ) {
	class Prevent_Direct_Access_Gold_Settings {
		public function display_tab() {
			if ( ! get_option( PDA_v3_Constants::LICENSE_OPTIONS ) ) {
				return 'license';
			} elseif ( ! Pda_Gold_Functions::is_fully_activated() ) {
				return 'helper';
			} elseif ( ! Pda_v3_Gold_Helper::is_migrated_data_from_v2() ) {
				return 'migration';
			} else {
				return 'general';
			}
		}

		public function render_ui() {
			$widgets = new Prevent_Direct_Access_Gold_Setting_Widgets(); ?>
			<div class="wrap">
				<div id="icon-themes" class="icon32"></div>
				<h2>Prevent Direct Access Gold <span
							class="pda-version"><?php _e( PDA_GOLD_V3_VERSION ) ?></span></h2>
				<?php
				$default_tab  = get_option( PDA_v3_Constants::LICENSE_OPTIONS ) ? $this->display_tab() : 'license';
				$activate_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $default_tab;
				$this->render_tabs( $activate_tab );
				$this->render_content( $activate_tab ); ?>
			</div>
			<div id="pda_right_column_metaboxes">
				<?php $widgets->render_notices(); ?>
				<?php $widgets->render_subscribe_form(); ?>
				<?php $widgets->render_like_plugin_column(); ?>
				<?php $widgets->render_invite_and_earn(); ?>
			</div>
			<?php
		}

		private function render_tabs( $active_tab ) {
			$settings = new Pda_Gold_Functions();
			$prefix   = PDA_v3_Constants::SETTING_PAGE_PREFIX;
			if ( ! get_option( PDA_v3_Constants::LICENSE_OPTIONS ) ) { ?>
				<h2 class="nav-tab-wrapper">
					<a href="?page=<?php echo $prefix ?>"
					   class="nav-tab <?php echo $active_tab == 'license' ? 'nav-tab-active' : ''; ?>"><?php _e( 'License', 'prevent-direct-access-gold' ); ?></a>
				</h2>
			<?php } elseif ( Pda_Gold_Functions::is_fully_activated() ) {
				if ( ! Pda_v3_Gold_Helper::is_migrated_data_from_v2() ) { ?>
					<h2 class="nav-tab-wrapper">
						<a href="?page=<?php echo $prefix ?>&tab=migration"
						   class="nav-tab <?php echo $active_tab == 'migration' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Migrate to version 3.0', 'prevent-direct-access-gold' ); ?></a>
					</h2>
				<?php } else { ?>
					<h2 class="nav-tab-wrapper">
						<?php if ( ! Pda_v3_Gold_Helper::is_migrated_data_from_v2() ) { ?>
							<a href="?page=<?php echo $prefix ?>&tab=migration"
							   class="nav-tab <?php echo $active_tab == 'migration' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Migrate to version 3.0', 'prevent-direct-access-gold' ); ?></a>
						<?php } ?>

						<?php if ( get_option( PDA_v3_Constants::LICENSE_OPTIONS ) ) { ?>
							<a href="?page=<?php echo $prefix ?>&tab=general"
							   class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General', 'prevent-direct-access-gold' ); ?></a>
							<a href="?page=<?php echo $prefix ?>&tab=ip_block"
							   class="nav-tab <?php echo $active_tab == 'ip_block' ? 'nav-tab-active' : ''; ?>"><?php _e( 'IP Restriction', 'prevent-direct-access-gold' ); ?></a>
						<?php } ?>

						<?php if ( Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) == - 1 && version_compare( PDA_IP_BLOCK_VERSION, '1.0.4' ) >= 0 ) { ?>
							<a href="?page=<?php echo $prefix ?>&tab=protect_folder"
							   class="nav-tab <?php echo $active_tab == 'protect_folder' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Folder Protection', 'prevent-direct-access-gold' ); ?></a>
						<?php } ?>

						<?php if ( Yme_Plugin_Utils::is_plugin_activated( 'magic_link' ) == - 1 ) { ?>
							<a href="?page=<?php echo $prefix ?>&tab=magic_link"
							   class="nav-tab <?php echo $active_tab == 'magic_link' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Magic Links', 'prevent-direct-access-gold' ); ?></a>
						<?php } ?>

						<?php if ( Yme_Plugin_Utils::is_plugin_activated( 'pda_contact_forms' ) === - 1 ) { ?>
							<a href="?page=<?php echo $prefix ?>&tab=contact_forms"
							   class="nav-tab <?php echo $active_tab == 'contact_forms' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Forms & ACF', 'prevent-direct-access-gold' ); ?></a>
						<?php } ?>

						<?php if ( Yme_Plugin_Utils::is_plugin_activated( 'membership' ) == - 1 ) { ?>
							<a href="?page=<?php echo $prefix ?>&tab=membership"
							   class="nav-tab <?php echo $active_tab == 'membership' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Memberships', 'prevent-direct-access-gold' ); ?></a>
						<?php } ?>

						<?php if ( Yme_Plugin_Utils::is_plugin_activated( 'pda_woocommerce' ) == - 1 ) { ?>
							<a href="?page=<?php echo $prefix ?>&tab=pda_woocommerce"
							   class="nav-tab <?php echo $active_tab == 'pda_woocommerce' ? 'nav-tab-active' : ''; ?>"><?php _e( 'WooCommerce', 'prevent-direct-access-gold' ); ?></a>
						<?php } ?>

						<?php if ( is_plugin_active( 'pda-4rum-integration/pda-4rum-integration.php' ) ) { ?>
							<a href="?page=<?php echo $prefix ?>&tab=pda_4rum_integration"
							   class="nav-tab <?php echo $active_tab == 'pda_4rum_integration' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Community', 'prevent-direct-access-gold' ); ?></a>
						<?php } ?>

						<?php if ( $settings->getSettings( PDA_v3_Constants::REMOTE_LOG ) == true ) { ?>
							<a href="?page=<?php echo $prefix ?>&tab=helper" id="helpers"
							   class="nav-tab <?php echo $active_tab == 'helper' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Helpers', 'prevent-direct-access-gold' ); ?></a>
						<?php } ?>

						<?php /* if ( Yme_Plugin_Utils::is_plugin_activated('pdas3') === -1 ) { ?>
                            <a href="?page=<?php echo $prefix ?>&tab=pdas3" class="nav-tab <?php echo $active_tab == 'pdas3' ? 'nav-tab-active' : ''; ?>"><?php _e('AWS S3', 'prevent-direct-access-gold'); ?></a>
                        <?php } */ ?>

						<a href="?page=<?php echo $prefix ?>&tab=faq"
						   class="nav-tab <?php echo $active_tab == 'faq' ? 'nav-tab-active' : ''; ?>"><?php _e( 'FAQ', 'prevent-direct-access-gold' ); ?></a>
						<a href="?page=<?php echo $prefix ?>&tab=license"
						   class="nav-tab <?php echo $active_tab == 'license' ? 'nav-tab-active' : ''; ?>"><?php _e( 'License', 'prevent-direct-access-gold' ); ?></a>
						<a href="?page=<?php echo $prefix ?>&tab=pda-quick-tour"
						   class="button button-primary pda-quick-tour <?php echo $active_tab == 'pda-quick-tour' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Quick Walkthrough', 'prevent-direct-access-gold' ); ?></a>
					</h2>
				<?php }
			} else { ?>
				<h2 class="nav-tab-wrapper">
					<a href="?page=<?php echo $prefix ?>"
					   class="nav-tab <?php echo $active_tab == 'helper' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Helpers', 'prevent-direct-access-gold' ); ?></a>
					<a href="?page=<?php echo $prefix ?>&tab=license"
					   class="nav-tab <?php echo $active_tab == 'license' ? 'nav-tab-active' : ''; ?>"><?php _e( 'License', 'prevent-direct-access-gold' ); ?></a>
				</h2>
			<?php }
		}

		private function render_content( $active_tab ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/class-prevent-direct-access-gold-setting-widgets.php';
			$widgets = new Prevent_Direct_Access_Gold_Setting_Widgets();
			switch ( $active_tab ) {
				case 'general':
					$widgets->render_general_tab();
					break;
				case 'migration':
					if ( Pda_v3_Gold_Helper::is_migrated_data_from_v2() ) {
						$widgets->render_general_tab();
					} else {
						$widgets->render_migration_tab();
					}
					break;
				case 'license':
					$widgets->render_license_tab();
					break;
				case 'ip_block':
					$widgets->render_ip_block_tab();
					do_action( 'pda_ip_block' );
					break;
				case 'magic_link':
					$widgets->render_magic_link_tab();
					break;
				case 'membership':
					$widgets->render_membership_tab();
					break;
				case 'helper':
					$widgets->render_helpers_tab();
					break;
				case 'contact_forms':
					$widgets->render_contact_forms_tab();
					break;
				case 'pda_woocommerce':
					$widgets->render_pda_woocommerce_tab();
					break;
				case 'pda-quick-tour':
					$widgets->render_quick_tour_tab();
					break;
				case 'protect_folder':
					$widgets->render_protect_all_file_in_folder_tab();
					break;
				case 'pda_4rum_integration':
					$widgets->render_pda_4rum_tab();
					break;
//                    case 'pdas3':
//                        do_action('pdas3_settings');
//                        break;
				default:
					$widgets->render_faq_tab();
					break;
			}
		}
	}
}
