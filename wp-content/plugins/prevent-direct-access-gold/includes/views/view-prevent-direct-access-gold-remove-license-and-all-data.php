<tr>
	<td>
		<?php if ( $setting->get_site_settings( PDA_v3_Constants::REMOVE_LICENSE_AND_ALL_DATA ) ) { ?>
			<label class="pda_switch" for="remove_license_and_all_data">
				<input type="checkbox" id="remove_license_and_all_data" name="remove_license_and_all_data" checked <?php echo $disabled_by_site ?>/>
				<span class="pda-slider round"></span>
			</label>
		<?php } else { ?>
			<label class="pda_switch" for="remove_license_and_all_data">
				<input type="checkbox" id="remove_license_and_all_data" name="remove_license_and_all_data" <?php echo $disabled_by_site ?>/>
				<span class="pda-slider round"></span>
			</label>
		<?php } ?>
		<div class="pda_error" id="pda_l_error"></div>
	</td>
    <td>
        <p class="<?php esc_attr_e( $disabled_color_class, 'prevent-direct-access-gold' ) ?>">
            <label><?php echo esc_html__( 'Remove Data Upon Uninstall', 'prevent-direct-access-gold' ) ?></label>
            <?php echo esc_html__( 'Remove your license and ALL related data upon uninstall. Your license may not be used on this website again or elsewhere anymore.', 'prevent-direct-access-gold' ) ?>
        </p>
    </td>
</tr>
