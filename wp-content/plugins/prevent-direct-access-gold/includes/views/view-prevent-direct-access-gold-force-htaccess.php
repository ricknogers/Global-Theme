<?php
$pda_force_style = $setting->getSettings( PDA_v3_Constants::REMOTE_LOG ) ? '' : 'style="display: none;"';
?>

<tr id="pda-force-htaccess" <?php echo $pda_force_style; ?>>
	<td>
		<?php if ( $setting->getSettings( PDA_v3_Constants::FORCE_PDA_HTACCESS ) ) { ?>
			<label class="pda_switch" for="force_pda_htaccess">
				<input type="checkbox" id="force_pda_htaccess" name="force_pda_htaccess" checked />
				<span class="pda-slider round"></span>
			</label>
		<?php } else { ?>
			<label class="pda_switch" for="force_pda_htaccess">
				<input type="checkbox" id="force_pda_htaccess" name="force_pda_htaccess" />
				<span class="pda-slider round"></span>
			</label>
		<?php } ?>
		<div class="pda_error" id="pda_l_error"></div>
	</td>
    <td>
        <p>
            <label><?php echo esc_html__( 'Force Local Rewrite Rules', 'prevent-direct-access-gold' ); ?></label>
	        <?php echo esc_html__( 'Create .htaccess', 'prevent-direct-access-gold' ); ?> <a rel="noopener noreferrer" target="_blank" href="https://preventdirectaccess.com/docs/pda-rewrite-rules/#rewrite-rules/">rewrite rules under _pda folder</a>. <?php echo esc_html__( 'Enable this option when protected links do not work due to caching issues and/or potential conflicts with other plugins.', 'prevent-direct-access-gold' ); ?>
        </p>
    </td>
</tr>
