<tr>
	<td>
		<label class="pda_switch" for="pda_prevent_access_license">
			<?php if ( $setting->get_site_settings( PDA_v3_Constants::PDA_PREVENT_ACCESS_LICENSE ) ) {?>
				<input type="checkbox" id="pda_prevent_access_license" checked <?php echo $disabled_by_site ?>/>
			<?php } else { ?>
				<input type="checkbox" id="pda_prevent_access_license" <?php echo $disabled_by_site ?>/>
			<?php } ?>
				<span class="pda-slider round"></span>
		</label>
	</td>
    <td>
        <p class="<?php esc_attr_e( $disabled_color_class, 'prevent-direct-access-gold' ) ?>">
            <label><?php echo esc_html__( 'Block Access to Sensitive Files', 'prevent-direct-access-gold' ) ?></label>
            <?php echo esc_html__( 'Block access to readme.html, license.txt, and wp-config-sample.php files', 'prevent-direct-access-gold' ) ?>
        </p>
    </td>
</tr>