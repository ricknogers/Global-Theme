<tr>
	<td>
		<label class="pda_switch" for="pda_prevent_access_version">
			<?php if ( $setting->getSettings( PDA_v3_Constants::PDA_PREVENT_ACCESS_VERSION ) ) {	?>
				<input type="checkbox" id="pda_prevent_access_version" checked/>
			<?php } else { ?>
				<input type="checkbox" id="pda_prevent_access_version"/>
			<?php } ?>
				<span class="pda-slider round"></span>
		</label>
	</td>
    <td>
        <p>
            <label><?php echo esc_html__( 'Hide WordPress Version', 'prevent-direct-access-gold' ) ?></label>
            <?php echo esc_html__( 'Remove WordPress generator meta tag showing its version and sensitive information', 'prevent-direct-access-gold' ) ?>
        </p>
    </td>
</tr>