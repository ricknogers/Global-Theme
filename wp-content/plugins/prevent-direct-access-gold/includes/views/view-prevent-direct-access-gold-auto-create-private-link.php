<tr>
	<?php if ( $setting->getSettings( PDA_v3_Constants::PDA_AUTO_CREATE_NEW_PRIVATE_LINK ) ) { ?>
		<td>
			<label class="pda_switch" for="pda_auto_create_new_private_link">
				<input type="checkbox" id="pda_auto_create_new_private_link"
							 name="pda_auto_create_new_private_link" checked/>
				<span class="pda-slider round"></span>
			</label>
		</td>
	<?php } else { ?>
		<td>
			<label class="pda_switch" for="pda_auto_create_new_private_link">
				<input type="checkbox" id="pda_auto_create_new_private_link"
							 name="pda_auto_create_new_private_link"/>
				<span class="pda-slider round"></span></label>
			</label>
		</td>
	<?php } ?>
    <td>
        <p>
            <label><?php echo esc_html__( 'Generate Download Link Once Protected', 'prevent-direct-access-gold' ) ?></label>
            <?php echo esc_html__( 'Automatically create a new Download Link once the file is protected', 'prevent-direct-access-gold' ) ?>
        </p>
    </td>
</tr>