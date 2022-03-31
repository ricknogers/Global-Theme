<?php
$use_redirect_url = $setting->get_site_settings( PDA_v3_Constants::USE_REDIRECT_URLS );
$checked          = $use_redirect_url ? 'checked' : '';

$rewrite_rule_checker = new PDA_v3_Rewrite_Rule_Checker();
$htaccess_result      = $rewrite_rule_checker->check_htaccess_file_in_folder_pda();

$pda_hide     = '';
$error_button = '';
$message      = '';
if ( $use_redirect_url ) {
	$message = pda_gold_render_notice_for_raw_url( $htaccess_result );
	if ( $htaccess_result[ PDA_v3_Constants::PDA_HTACCESS_RAW_URL_ERROR ] ) {
		$error_button = explode( ':', $message )[0];
	} else {
		$pda_hide = 'pda-display-none';
	}
} else {
	if ( ! $rewrite_rule_checker->allow_access_pda_folder() ) {
		$message      = PDA_v3_Constants::PDA_MESSAGE_REMOVE_HTACCESS_FILE_IN_PDA_FOLDER;
		$error_button = explode( ':', $message )[0];
	} else {
		$pda_hide = 'pda-display-none';
	}
}

function pda_gold_render_notice_for_raw_url( $htaccess_result ) {
	if ( $htaccess_result[ PDA_v3_Constants::PDA_HTACCESS_RAW_URL_ERROR ] ) {
		return $htaccess_result[ PDA_v3_Constants::PDA_HTACCESS_MESSAGE ];
	}
}

?>
<tr>
	<td>
		<label class="pda_switch" for="use_redirect_urls">
			<input type="checkbox"
			       id="use_redirect_urls" <?php echo esc_attr( $checked . ' ' . $disabled_by_site ); ?>/>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<p class="<?php esc_attr_e( $disabled_color_class ); ?>">
			<label><?php echo esc_html__( 'Keep Raw URLs', 'prevent-direct-access-gold' ); ?><span
						class="pda_button_error_raw_url <?php echo esc_attr( $pda_hide ); ?>"><?php echo esc_html( $error_button, 'prevent-direct-access-gold' ); ?></span></label>
			<?php _e( 'Use Raw URLs for both <a rel="noopener noreferrer" target="_blank" href="https://preventdirectaccess.com/pda-gold-glossary/">protected and private download links</a>. Enable this option ONLY if you are using Wordpress.com or other servers that don’t allow rewrite rules modification.', 'prevent-direct-access-gold' ); ?>
			<span class="pda_raw_url_error"><?php _e( $message, 'prevent-direct-access-gold' ); ?></span>
		</p>
	</td>
</tr>
