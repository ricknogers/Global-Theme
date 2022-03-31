<tr>
	<td>
		<label class="pda_switch" for="pda_gold_enable_directory_listing">
			<?php if ( $setting->get_site_settings( PDA_v3_Constants::PDA_GOLD_ENABLE_DERECTORY_LISTING ) ) { ?>
				<input type="checkbox" id="pda_gold_enable_directory_listing" checked <?php echo $disabled_by_site ?>/>
			<?php } else {?>
				<input type="checkbox" id="pda_gold_enable_directory_listing" <?php echo $disabled_by_site ?>/>
			<?php } ?>
				<span class="pda-slider round"></span>
		</label>
	</td>
    <td>
        <p class="<?php esc_attr_e( $disabled_color_class, 'prevent-direct-access-gold' ) ?>">
            <label><?php echo esc_html__( 'Disable Directory Listing', 'prevent-direct-access-gold' ) ?></label>
            <?php echo esc_html__( 'Disable directory browsing of all folders and subdirectories', 'prevent-direct-access-gold' ) ?>
        </p>
    </td>
</tr>