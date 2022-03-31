<tr>
    <td colspan="2"><h3><?php echo esc_html__( 'Multisite - Site Rules', 'prevent-direct-access-gold' ) ?></h3></td>
</tr>
<tr>
    <td class="feature-input"><span class="feature-input"></span></td>
    <td>
        <textarea readonly rows="10" class="pda-textarea-for-multisite"><?php echo esc_html__( $guides, 'prevent-direct-access-gold' ) ?></textarea>
        <p class="description">	<?php echo esc_html__( 'Please update these rules on your main .htaccess file as per our', 'prevent-direct-access-gold' ) ?>
            <a href="https://preventdirectaccess.com/docs/how-to-protect-your-media-files-on-wordpress-multisite/"><?php echo esc_html__( 'instructions', 'prevent-direct-access-gold' ) ?></a>.
	        <?php if ( Pda_Gold_Functions::is_show_notice_for_multisite() ) {
		        /* translators: %1$s The guide link */
		        $message = sprintf( __( 'Please install <a target="_blank" rel="noopener" href="%s">PDA Multisite extentsion</a> for our file protection to work properly.', 'prevent-direct-access-gold' ), 'https://preventdirectaccess.com/extensions/wordpress-multisite-integration/' );
	        	?>
		        <br>
	            <span style="color: red">
	                <?php echo $message; ?>
	            </span>
		        <?php
	        } ?>
        </p>
    </td>
</tr>
<?php if ( is_multisite() && get_current_blog_id() === 1 && is_super_admin( wp_get_current_user()->ID ) ) {?>
    <tr>
        <td>
            <label class="pda_switch" for="pda_gold_enable_auto_activate_new_site">
                <?php if (  $setting->getSettings( PDA_v3_Constants::PDA_AUTO_ACTIVATE_NEW_SITE ) ) { ?>
                    <input type="checkbox" id="pda_gold_enable_auto_activate_new_site" checked/>
                <?php } else { ?>
                    <input type="checkbox" id="pda_gold_enable_auto_activate_new_site"/>
                <?php } ?>
                <span class="pda-slider round"></span>
            </label>
        </td>
        <td>
            <p>
                <label><?php echo esc_html_e( 'Auto-activate license on new sites', 'prevent-direct-access-gold' ) ?></label>
                <?php echo esc_html_e( 'Automatically activate main site\'s license on new subsites', 'prevent-direct-access-gold' ) ?>

            </p>
        </td>
    </tr>
<?php } ?>
<tr>
    <td colspan="2">
        <hr>
    </td>
</tr>
