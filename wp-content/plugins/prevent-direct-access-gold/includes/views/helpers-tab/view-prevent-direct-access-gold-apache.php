<?php
$rewrite_file_type = '<code>.htaccess</code>';
$rewrite_file_loc  = '<code>' . $home_path . '</code>';
$rewrite_rule_loc  = sprintf( wp_kses( __( '<strong>within your WordPress rewrite block</strong>, which usually starts with %s and ends with %s, and <strong>just below</strong> the line reading %s', 'prevent-direct-access-gold' ), array( 'strong' => array() ), false ), '<code># BEGIN WordPress</code>', '<code># END WordPress</code>', '<code>RewriteRule ^index\.php$ - [L]</code>' );

if ( ! is_multisite() && ! get_option( 'permalink_structure' ) ) {
	$rewrite_rule_loc = __( '<strong>above</strong> any other rewrite rules in the file.', 'prevent-direct-access-gold' );

	printf( wp_kses( __( 'PDA Gold works best with %s enabled, so it is strongly recommended that you %s! If, however, you really <i>really</i> want to use ugly permalinks, then...', 'prevent-direct-access-gold' ), array( 'i' => array() ), false ), '<a href="http://codex.wordpress.org/Introduction_to_Blogging#Pretty_Permalinks" target="_blank">' . esc_html__( 'Pretty Permalinks', 'prevent-direct-access-gold' ) . '</a>', '<a href="http://codex.wordpress.org/Using_Permalinks" target="_blank">' . esc_html__( 'enable them', 'prevent-direct-access-gold' ) . '</a>' );
	echo "\n";
}

if ( Pda_Gold_Functions::is_fully_activated() ) {
	printf( _e( "The following rules should be added into your .htaccess file located at $rewrite_file_loc just <b>below</b> this line <code>RewriteRule ^index\.php$ - [L]</code>", 'prevent-direct-access-gold' ), $rewrite_file_type, $rewrite_file_loc );
	?> <br /> <?php
	_e( "Please note that even though our \"Prevent Direct Access Rewrite Rules\" is inserted correctly and our plugin is working properly, you might <a href='https://preventdirectaccess.com/docs/what-happens-when-enabling-debug-logs/#rewrite-rules'>come across these error messages</a> if other rules are inserted wrongly.");

} else {
	if ( ! is_multisite() ) {
		_e( 'Add the following rules to your ' . $rewrite_file_type . ' file located at ' . $rewrite_file_loc . ' just <b>below</b> this line <code>RewriteRule ^index\.php$ - [L]</code>', 'prevent-direct-access-gold' );
	} else {
		_e( 'Update our rewrite rules on your WordPress Multisite Network <a href="https://preventdirectaccess.com/docs/how-to-protect-your-media-files-on-wordpress-multisite/" target="_blank" rel="noopener noreferrer">as per this instruction</a>', 'prevent-direct-access-gold' );
	}
}

$rules = Prevent_Direct_Access_Gold_Htaccess::get_the_rewrite_rules(); ?>
<textarea style="margin-top: 10px" class="code" readonly="readonly" cols="90"
          rows="<?php echo count( $rules ); ?>"><?php echo esc_textarea( implode( "\n", $rules ) ); ?></textarea>
</p>

