<?php
$license_expired = Pda_Gold_Functions::is_license_expired() || Pda_v3_Gold_Helper::blocked_download();
$license_label   = "License key";
$settings        = new Pda_Gold_Functions();
$plugin_version  = Pda_v3_Gold_Helper::get_plugin_version();
$tooltip         = new Prevent_Direct_Access_Gold_Setting_Widgets();

?>
<div class="main_container">
	<form id="pda_license_form" class="pda-license-container">
		<?php if ((!get_option(PDA_v3_Constants::LICENSE_OPTIONS))) {
			echo("<h3>" . PDA_v3_Constants::LICENSE_NOT_ACTIVATED . "</h3>");
		} ?>

		<input type="hidden" value="<?php echo wp_create_nonce(PDA_v3_Constants::LICENSE_FORM_NONCE); ?>"  id="prevent-direct-access-gold_nonce"/>

		<?php if ((!empty($license) && !is_null($license))) { ?>
			<div class="pda-license-info">
				<label>
					Prevent Direct Access</label>
				<span><i class="fa fa-star" aria-hidden="true"></i> Gold version <?php echo esc_html($plugin_version)?></span>
			</div>
			<div class="pda-license-info">
				<label>License type</label>
				<span><?php echo $license_type ?></span>
			</div>
        <?php } ?>

		<div class="pda-license-info">
			<label><?php echo esc_html( $license_label) ?></label>
			<?php if (get_option(PDA_v3_Constants::LICENSE_KEY) && !$configs->debug_mode) {
				echo("<span>" . get_option(PDA_v3_Constants::LICENSE_KEY) . "</span>" );
				?>
				<?php if ( $settings->getSettings( PDA_v3_Constants::REMOTE_LOG ) && ! empty( $license ) ){ ?>
					<a id="btn-recheck-license" style="cursor: pointer;"><?php _e( '(Refresh license)', 'prevent-direct-access-gold' ); ?></a>
				<?php } ?>
				<?php
			} elseif ($configs->debug_mode) {
				echo('<td><input required style="width: 330px" type="text" id="prevent-direct-access-gold_license_key" name="prevent-direct-access-gold_license_key" value="' . $license  . '" /><div class="prevent-direct-access-gold_error" id="prevent-direct-access-gold_l_error"></div></td>');
			} else {
				echo('<td><input required style="width: 330px" type="text" id="prevent-direct-access-gold_license_key" name="prevent-direct-access-gold_license_key" value="" /><div class="prevent-direct-access-gold_error" id="prevent-direct-access-gold_l_error"></div></td>');
			} ?>
		</div>
        <?php if ( $license_expired ) { ?>
	        <div class="pda-license-info">
		        <label>Select a new license type</label>
		        <td>
			        <select name="product_id" id="product_id" form="pda_license_form">
				        <option value="583147">1-site yearly subscription license</option>
				        <option value="584087">3-site yearly subscription license</option>
				        <option value="78013383">10-site yearly subscription license</option>
				        <option value="77844608">15-site yearly subscription license</option>
				        <option value="584088">Unlimited-site license</option>
				        <option value="77814469">Developer license</option>
				        <option value="77917258">1-site lifetime license</option>
				        <option value="77917256">3-site lifetime license</option>
				        <option value="78013421">10-site lifetime license</option>
				        <option value="77917246">15-site lifetime license</option>
			        </select>
		        </td>
	        </div>
            <div class="pda-license-info">
                <label>Input a new license key</label>
                <td><input required style="width: 330px" type="text" id="prevent-direct-access-gold_license_key" name="prevent-direct-access-gold_license_key" value="" /><div class="prevent-direct-access-gold_error" id="prevent-direct-access-gold_l_error"></div></td>
            </div>
        <?php } ?>
        <?php if ( isset( $license_info->expired_date ) && ! $is_lifetime && ( time() > $license_info->expired_date ) ) {
            ?>
            <div class="pda-license-info">
                <label><?php esc_html_e( 'Expiry date', 'prevent-direct-access-gold' ) ?></label>
                <span><?php echo esc_html( Pda_v3_Gold_Helper::timestamp_to_local_date( $license_info->expired_date ) ) ?></span>
            </div>
            <?php
        }?>
        <?php if( isset( $license_info->addons ) ) {
            $addons = Pda_v3_Gold_Helper::map_addons_id( $license_info->addons );
            if ( ! empty( $addons ) ) {
	            ?>
                <div class="pda-license-info purchased-addons">
                    <label><?php echo esc_html__('Purchased addons', 'prevent-direct-access-gold')?></label>
                    <span><?php echo esc_html( $addons ) ?></span>
                </div>
	            <?php
            }
        } ?>
		<?php if (!get_option(PDA_v3_Constants::LICENSE_OPTIONS) ||  $configs->debug_mode || $license_expired ) {
			?>
			<div class="pda-license-info">
				<label></label>
				<?php submit_button(); ?>
			</div>
			<?php
		} ?>

        <?php if ( is_multisite() && get_current_blog_id() === 1 && is_super_admin( wp_get_current_user()->ID ) && ( !empty($license) && !is_null($license) ) ) { ?>
            <div class="pda-license-info">
                <label>
                    Subsites license <?php $tooltip->render_tooltip( 'activate_all_sites' ) ?>
                </label>
                <span id="span-activate">
                    <input id="activate-all-sites" type="submit" value="<?php _e( 'Activate Now', 'prevent-direct-access-gold' ); ?>" class="button button-primary" />
                </span>
            </div>
        <?php } ?>
        <div id="info-site-activated" class="pda-license-info pda-display-none">
            <label>
                Subsites activated <?php $tooltip->render_tooltip('count_activated_for_multisite') ?>
            </label>
            <span id="site-activated">
                0
            </span>
        </div>
	</form>
</div>
