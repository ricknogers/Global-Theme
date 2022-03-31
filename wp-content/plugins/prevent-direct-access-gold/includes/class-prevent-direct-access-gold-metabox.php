<?php
/**
 * Created by PhpStorm.
 * User: gaupoit
 * Date: 5/30/18
 * Time: 09:52
 */

add_meta_box(
	'pda_v3_protection_metabox',
	'Prevent Direct Access',
	'pda_v3_render_attachment_protection_setting',
	'attachment',
	'side'
);


/**
 * Function to render metabox protection setting option
 *
 * @param $post
 */

if ( ! function_exists( 'pda_v3_render_attachment_protection_setting' ) ) {
	function pda_v3_render_attachment_protection_setting( $post ) {
		$repo = new PDA_v3_Gold_Repository();
		wp_nonce_field( 'pda_v3_protection_metabox', PDA_v3_Constants::METABOX_OPTION_NONCE );
		$is_protected = $repo->is_protected_file( $post->ID );

		$data['post_id'] = $post->ID;
		if ( Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) == - 1 ) {
			$admin      = new Wp_Pda_Ip_Block_Admin( '', '' );
			$data_roles = $admin->get_user_roles_ip_block( $data );
		} else {
			$services   = new PDA_Services();
			$data_roles = $services->get_fap( $post->ID );
		}

		$type_select       = $data_roles['type'];
		$ip_block_disabled = Yme_Plugin_Utils::is_plugin_activated( 'ip_block' ) == - 1 ? '' : 'disabled';

		include PDA_V3_BASE_DIR . '/includes/views/view-prevent-direct-access-gold-attchment-protection.php';
	}
}

