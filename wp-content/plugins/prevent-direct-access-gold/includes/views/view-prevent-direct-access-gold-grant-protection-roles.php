<tr>
	<td class="feature-input"><span class="feature-input"></span></td>
	<td>
		<p>
			<label><?php echo esc_html_e( 'File Protection Control', 'prevent-direct-access-gold' ) ?></label>
			<?php echo esc_html_e( 'Select user roles who ', 'prevent-direct-access-gold' ) ?>
			<a href="https://preventdirectaccess.com/docs/settings/#file-protection-control" target="_blank"><?php echo esc_html_e( 'can protect or unprotect your media files', 'prevent-direct-access-gold' ) ?></a><?php echo esc_html_e( '. Default: admins (always included), authors and editors.', 'prevent-direct-access-gold' ) ?>
		</p>
        <span class="pda-admin-fpc">Administrator</span>
		<select multiple="multiple" id="pda_role_protection" class="pda_select2_for_role_protection">
			<?php foreach ( $roles as $role_name => $role_info ):
                if ( $role_name !== 'administrator' ) {
				$arrRole = array( $role_name ); ?>
				<option <?php echo array_intersect( $arrRole, $setting->selected_roles( PDA_v3_Constants::PDA_GOLD_ROLE_PROTECTION ) ) ? 'selected="selected"' : '' ?> value="<?php echo esc_attr ( $role_name ) ?>"><?php echo esc_html__( $role_name ) ?></option>
			<?php } endforeach; ?>
		</select>
	</td>
</tr>
