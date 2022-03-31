<?php
$supported_crawlers = Pda_Gold_Functions::get_supported_crawlers();
$selected_crawlers  = $setting->get_site_setting_type_is_array( PDA_v3_Constants::PDA_GOLD_WEB_CRAWLERS );
$enabled  = $setting->get_site_settings( PDA_v3_Constants::PDA_GOLD_ENABLE_WEB_CRAWLERS );
$display  = $enabled ? '' : 'style=display:none';
$required = $enabled ? 'required' : '';
?>
<tr>
	<td>
		<label class="pda_switch" for="pda_enable_wc">
			<?php if ( $enabled ) { ?>
				<input type="checkbox" id="pda_enable_wc" checked <?php echo esc_attr( $disabled_by_site ); ?>/>
			<?php } else { ?>
				<input type="checkbox" id="pda_enable_wc" <?php echo esc_attr( $disabled_by_site ); ?>/>
			<?php } ?>
			<span class="pda-slider round"></span>
		</label>
	</td>
	<td>
		<div class="<?php echo esc_attr( $disabled_color_class ); ?>">
			<label><?php echo esc_html__( 'Grant Web Crawlers Access', 'prevent-direct-access-gold' ); ?></label>
			<?php echo esc_html__( 'Select which search engines and social network bots ', 'prevent-direct-access-gold' ); ?>
			<a href="https://preventdirectaccess.com/docs/settings/#crawler" target="_blank"
			   rel="noopener"><?php echo esc_html__( 'can access your protected files', 'prevent-direct-access-gold' ) ?></a>
		</div>
	</td>
</tr>
<tr id="pda-web-crawler" class="<?php esc_attr_e( $disabled_color_class ) ?>" <?php echo esc_attr( $display ); ?> >
	<td></td>
	<td>
		<!-- <p>
			<?php esc_html__( 'Allow these crawlers only', 'prevent-direct-access-gold' ); ?>
		</p> -->
		<select
				id="pda-selected-wc"
				class="pwc-opt pda_select2"
				multiple="multiple"
				<?php echo esc_attr( $disabled_by_site ) ?>
			<?php echo esc_attr( $required ); ?>
		>
			<?php foreach ( $supported_crawlers as $crawler ) : ?>
				<?php $selected = in_array( $crawler['value'], $selected_crawlers, true ) ? 'selected' : ''; ?>
				<option
						value="<?php echo esc_attr( $crawler['value'] ); ?>"
					<?php echo esc_attr( $selected ); ?>
				>
					<?php echo esc_html( $crawler['name'] ); ?>
				</option>
			<?php endforeach; ?>
		</select>
	</td>
</tr>
