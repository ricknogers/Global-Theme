<tr>
	<td>
		<label class="pda_switch" for="pda_gold_enable_image_hot_linking">
			<?php if ( $setting->get_site_settings( PDA_v3_Constants::PDA_GOLD_ENABLE_IMAGE_HOT_LINKING ) ) { ?>
				<input type="checkbox" id="pda_gold_enable_image_hot_linking" checked <?php echo $disabled_by_site ?>/>
			<?php } else { ?>
				<input type="checkbox" id="pda_gold_enable_image_hot_linking" <?php echo $disabled_by_site ?>/>
			<?php } ?>
				<span class="pda-slider round"></span>
		</label>
	</td>
    <td>
        <p class="<?php esc_attr_e( $disabled_color_class, 'prevent-direct-access-gold' ) ?>">
            <label><?php echo esc_html__( 'Prevent Image Hotlinking', 'prevent-direct-access-gold' ) ?></label>
            <?php echo esc_html__( 'Prevent other people from stealing and using your images or files without permission', 'prevent-direct-access-gold' ) ?>
        </p>
    </td>
</tr>