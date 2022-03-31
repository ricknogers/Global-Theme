<p>
	<?php
	if ( 'apache' === $server_name && is_multisite() ) {
		$open_message = "It looks like you're using WordPress Multisite Network. However, Prevent Direct Access Gold cannot rewrite the .htaccess file automatically. Here's how you can do it manually:";
	} else if ( 'nginx' === $server_name ) {
		$open_message = "It looks like you're using NGINX webserver. Since NGINX doesn’t have .htaccess-type capability, Prevent Direct Access Gold cannot modify your server configuration automatically for you. Here's how you can do it manually:";
	}

	esc_html_e( $open_message, 'prevent-direct-access-gold' );
	if ( is_writable( $home_path . '.htaccess' ) ) {
		$errors['Nonwritable'] = sprintf( esc_html__( 'the site\'s %s file is not writable, as in: "the site\'s .htaccess file is not writable "', 'prevent-direct-access-gold' ), '<code>.htaccess</code>' );
	}
	if ( ( 'apache' === $server_name && ! is_multisite() ) || 'iis' === $server_name ) {
		esc_html_e( $end_message, 'prevent-direct-access-gold' );
	}
	?>
</p>
<ol>
	<li>
		<?php self::render_guides_after_fully_activated( $server_name, $home_path ); ?>
	</li>
	<?php if ( Pda_Gold_Functions::is_show_notice_for_multisite() ) {
		/* translators: %1$s The guide link */
		$message = sprintf( __( 'Please install <a target="_blank" rel="noopener" href="%s">PDA Multisite extentsion</a> for our file protection to work properly.', 'prevent-direct-access-gold' ), 'https://preventdirectaccess.com/extensions/wordpress-multisite-integration/' );
		?>
		<p style="color: red" class="description">
			<?php echo $message; ?>
		</p>
		<?php
	} ?>
	<li>
		<p>
			<?php esc_html_e( 'Once done, please click on the button below to check if the rewrite rules are inserted correctly', 'prevent-direct-access-gold' ); ?>
		</p>
		<form method="post" id="enable_pda_v3_form">
			<?php wp_nonce_field( 'pda_ajax_nonce_v3', 'nonce_pda_v3' ); ?>
			<?php submit_button( __( $btn_name, 'prevent-direct-access-gold' ), 'primary', 'enable_pda_v3', false ); ?>
		</form>
	</li>
</ol>
<p>
	Or use raw
	URLs <?php echo 'apache' !== $server_name && 'iis' !== $server_name ? 'with <a target="_blank" rel="noopener noreferrer" href="https://preventdirectaccess.com/docs/pda-rewrite-rules/#raw-url-limitation">some limitation</a>' : ''; ?>
<div>
	<form method="post" id="enable_pda_v3_raw_url">
		<?php wp_nonce_field( 'pda_ajax_nonce_v3', 'nonce_pda_v3' ); ?>
		<?php submit_button( __( 'Use Raw URLs', 'prevent-direct-access-gold' ), 'primary', 'enable_raw_url', false ); ?>
	</form>
</div>
</p>

